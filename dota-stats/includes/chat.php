<?php
/*********
*
- Modified for GHost++ Replays by googlexx
-------------------------------------------------------------------------------
Based on:
- RESHINE parser - http://reshine.bunglon.net
- Warcraft III Replay Parser By Julas  -  http://toya.net.pl/~julas/w3g/
- DOTA Replay Parser By Rush4Hire   -  http://rush4hire.com
*
*********/

require('convert-chat.php');

// to know when there is a need to load next block
define('MAX_DATABLOCK', 1500);
// for preventing duplicated actions
define('ACTION_DELAY', 1000);
// to know how long it may take after buying a Tome of Retraining to retrain a hero
define('RETRAINING_TIME', 15000);

class replay {
	var $fp, $data, $leave_unknown, $continue_game, $referees, $time, $pause, $leaves, $errors, $header, $game,  $players, $chat, $filename, $parse_actions, $parse_chat;
	var $max_datablock = MAX_DATABLOCK;
	
	function replay($filename, $parse_actions=true, $parse_chat=true) {
		$this->parse_actions = $parse_actions;
		$this->parse_chat = $parse_chat;
		$this->filename = $filename;
		$this->game['player_count'] = 0;
		if (!$this->fp = fopen($filename, 'rb')) {
			exit($this->filename.': Can\'t read replay file');
		}
		flock($this->fp, 1);
	
		$this->parseheader();
		$this->parsedata();
		$this->cleanup();
	
		flock($this->fp, 3);
		fclose($this->fp);
		unset($this->fp);
		unset($this->data);
		unset($this->players);
		unset($this->referees);
		unset($this->time);
		unset($this->pause);
		unset($this->leaves);
		unset($this->max_datablock);
		unset($this->ability_delay);
		unset($this->leave_unknown);
		unset($this->continue_game);
	}
	
	// 2.0 [Header]
	function parseheader() {
		$data = fread($this->fp, 48);
		$this->header = @unpack('a28intro/Vheader_size/Vc_size/Vheader_v/Vu_size/Vblocks', $data);
	
		if ($this->header['intro'] != "Warcraft III recorded game\x1A") {
			exit('Not a replay file');
		}
	
		if ($this->header['header_v'] == 0) {
			$data = fread($this->fp, 16);
			$this->header = array_merge($this->header, unpack('vminor_v/vmajor_v/vbuild_v/vflags/Vlength/Vchecksum', $data));
			$this->header['ident'] = 'WAR3';
		} elseif ($this->header['header_v']==1) {
			$data = fread($this->fp, 20);
			$this->header = array_merge($this->header, unpack('a4ident/Vmajor_v/vbuild_v/vflags/Vlength/Vchecksum', $data));
			$this->header['minor_v'] = 0;
			$this->header['ident'] = strrev($this->header['ident']);
		}
	}
	
	function parsedata() {
		fseek($this->fp, $this->header['header_size']);
		$blocks_count = $this->header['blocks'];
		for ($i=0; $i<$blocks_count; $i++) {
			// 3.0 [Data block header]
			$block_header = @unpack('vc_size/vu_size/Vchecksum', fread($this->fp, 8));
			$temp = fread($this->fp, $block_header['c_size']);
			$temp = substr($temp, 2, -4);
			// the first bit must be always set, but already set in replays with modified chatlog (why?)
			$temp{0} = chr(ord($temp{0}) | 1);
			if ($temp = gzinflate($temp)) {
				$this->data .= $temp;
			} else {
				exit($this->filename.': Incomplete replay file');
			}
	
			// 4.0 [Decompressed data]
			if ($i == 0) {
				$this->data = substr($this->data, 4);
				$this->loadplayer();
				$this->loadgame();
			} elseif ($blocks_count - $i < 2) {
				$this->max_datablock = 0;
			}
	
			if ($this->parse_chat || $this->parse_actions) {
				$this->parseblocks();
			} else {
				break;
			}
		}
	}
	
	// 4.1 [PlayerRecord]
	function loadplayer() {
		$temp = unpack('Crecord_id/Cplayer_id', $this->data);
		$this->data = substr($this->data, 2);
		$player_id = $temp['player_id'];
		$this->players[$player_id]['player_id'] = $player_id;
		$this->players[$player_id]['initiator'] = convert_bool(!$temp['record_id']);
	
		$this->players[$player_id]['name'] = '';
		for ($i=0; $this->data{$i}!="\x00"; $i++) {
			$this->players[$player_id]['name'] .= $this->data{$i};
		}
		// if it's FFA we need to give players some names
		if (!$this->players[$player_id]['name']) {
			$this->players[$player_id]['name'] = 'Player '.$player_id;
		}
		$this->data = substr($this->data, $i+1);
	
	
		if (ord($this->data{0}) == 1) { // custom game
			$this->data = substr($this->data, 2);
		} elseif (ord($this->data{0}) == 8) { // ladder game
			$this->data = substr($this->data, 1);
			$temp = unpack('Vruntime/Vrace', $this->data);
			$this->data = substr($this->data, 8);
			$this->players[$player_id]['exe_runtime'] = $temp['runtime'];
			$this->players[$player_id]['race'] = convert_race($temp['race']);
		}
		if ($this->parse_actions) {
			$this->players[$player_id]['actions'] = 0;
		}
		if (!$this->header['build_v']) { // calculating team for tournament replays from battle.net website
			$this->players[$player_id]['team'] = ($player_id-1)%2;
		}
		$this->game['player_count']++;
	}
	
	function loadgame() {
		// 4.2 [GameName]
		$this->game['name'] = '';
		for ($i=0; $this->data{$i}!=chr(0); $i++) {
			$this->game['name'] .= $this->data{$i};
		}
		$this->data = substr($this->data, $i+2); // 0-byte ending the string + 1 unknown byte
	
		// 4.3 [Encoded String]
		$temp = '';
	
		for ($i=0; $this->data{$i} != chr(0); $i++) {
			if ($i%8 == 0) {
				$mask = ord($this->data{$i});
			} else {
				$temp .= chr(ord($this->data{$i}) - !($mask & (1 << $i%8)));
			}
		}
		$this->data = substr($this->data, $i+1);
	
		// 4.4 [GameSettings]
		$this->game['speed'] = convert_speed(ord($temp{0}));
		
		if (ord($temp{1}) & 1) {
			$this->game['visibility'] = convert_visibility(0);
		} else if (ord($temp{1}) & 2) {
			$this->game['visibility'] = convert_visibility(1);
		} else if (ord($temp{1}) & 4) {
			$this->game['visibility'] = convert_visibility(2);
		} else if (ord($temp{1}) & 8) {
			$this->game['visibility'] = convert_visibility(3);
		}
		$this->game['observers'] = convert_observers(((ord($temp{1}) & 16) == true) + 2*((ord($temp{1}) & 32) == true));
		$this->game['teams_together'] = convert_bool(ord($temp{1}) & 64);
		
		$this->game['lock_teams'] = convert_bool(ord($temp{2}));
		
		$this->game['full_shared_unit_control'] = convert_bool(ord($temp{3}) & 1);
		$this->game['random_hero'] = convert_bool(ord($temp{3}) & 2);
		$this->game['random_races'] = convert_bool(ord($temp{3}) & 4);
		if (ord($temp{3}) & 64) {
			$this->game['observers'] = convert_observers(4);
		}
	
		$temp = substr($temp, 13); // 5 unknown bytes + checksum
		
		// 4.5 [Map&CreatorName]
		$temp = explode(chr(0), $temp);
		$this->game['creator'] = $temp[1];
		$this->game['map'] = $temp[0];
	
		// 4.6 [PlayerCount]
		$temp = unpack('Vslots', $this->data);
		$this->data = substr($this->data, 4);
		$this->game['slots'] = $temp['slots'];
	
		// 4.7 [GameType]
		$this->game['type'] = convert_game_type(ord($this->data[0]));
		$this->game['private'] = convert_bool(ord($this->data[1]));
	
		$this->data = substr($this->data, 8); // 2 bytes are unknown and 4.8 [LanguageID] is useless
	
		// 4.9 [PlayerList]
		while (ord($this->data{0}) == 0x16) {
			$this->loadplayer();
			$this->data = substr($this->data, 4);
		}
	
		// 4.10 [GameStartRecord]
		$temp = unpack('Crecord_id/vrecord_length/Cslot_records', $this->data);
		$this->data = substr($this->data, 4);
		$this->game = array_merge($this->game, $temp);
		$slot_records = $temp['slot_records'];
	
		// 4.11 [SlotRecord]
		for ($i=0; $i<$slot_records; $i++) {
			if ($this->header['major_v'] >= 7) {
				$temp = unpack('Cplayer_id/x1/Cslot_status/Ccomputer/Cteam/Ccolor/Crace/Cai_strength/Chandicap', $this->data);
				$this->data = substr($this->data, 9);
			} elseif ($this->header['major_v'] >= 3) {
				$temp = unpack('Cplayer_id/x1/Cslot_status/Ccomputer/Cteam/Ccolor/Crace/Cai_strength', $this->data);
				$this->data = substr($this->data, 8);
			} else {
				$temp = unpack('Cplayer_id/x1/Cslot_status/Ccomputer/Cteam/Ccolor/Crace', $this->data);
				$this->data = substr($this->data, 7);
			}
			$temp['color'] = convert_color($temp['color']);
			$temp['race'] = convert_race($temp['race']);
			$temp['ai_strength'] = convert_ai($temp['ai_strength']);
			if ($temp['slot_status'] == 2) { // do not add empty slots
				$this->players[$temp['player_id']] = array_merge($this->players[$temp['player_id']], $temp);
				// Tome of Retraining
				$this->players[$temp['player_id']]['retraining_time'] = 0;
			}
		}
	
		// 4.12 [RandomSeed]
		$temp = unpack('Vrandom_seed/Cselect_mode/Cstart_spots', $this->data);
		$this->data = substr($this->data, 6);
		$this->game['random_seed'] = $temp['random_seed'];
		$this->game['select_mode'] = convert_select_mode($temp['select_mode']);
		if ($temp['start_spots'] != 0xCC) { // tournament replays from battle.net website don't have this info
			$this->game['start_spots'] = $temp['start_spots'];
		}
	}
	
	// 5.0 [ReplayData]
	function parseblocks() {
		$data_left = strlen($this->data);
		while ($data_left > $this->max_datablock) {
			//$prev = $block_id;
			$block_id = ord($this->data{0});
	
			switch ($block_id) {
				// TimeSlot block
				case 0x1E:
				case 0x1F:
					$temp = unpack('x1/vlength/vtime_inc', $this->data);
					if (!$this->pause) {
						$this->time += $temp['time_inc'];
					}
					if ($temp['length'] > 2 && $this->parse_actions) {
						$this->parseactions(substr($this->data, 5, $temp['length']-2), $temp['length']-2);
					}
					$this->data = substr($this->data, $temp['length']+3);
					$data_left -= $temp['length']+3;
					break;
				// Player chat message (patch version >= 1.07)
				case 0x20:
					// before 1.03 0x20 was used instead 0x22
					if ($this->header['major_v'] > 2) {
						$temp = unpack('x1/Cplayer_id/vlength/Cflags/vmode', $this->data);
						if ($temp['flags'] == 0x20) {
							$temp['mode'] = convert_chat_mode($temp['mode']);
							$temp['text'] = substr($this->data, 9, $temp['length']-6);
						} elseif ($temp['flags'] == 0x10) {
							// those are strange messages, they aren't visible when
							// watching the replay but they are present; they have no mode
							$temp['text'] = substr($this->data, 7, $temp['length']-3);
							unset($temp['mode']);
						}
						$this->data = substr($this->data, $temp['length']+4);
						$data_left -= $temp['length']+4;
						$temp['time'] = $this->time;
						$temp['player_name'] = $this->players[$temp['player_id']]['name'];
						$this->chat[] = $temp;
						break;
					}
				// unknown (Random number/seed for next frame)
				case 0x22:
					$temp = ord($this->data{1});
					$this->data = substr($this->data, $temp+2);
					$data_left -= $temp+2;
					break;
				// unknown (startblocks)
				case 0x1A:
				case 0x1B:
				case 0x1C:
					$this->data = substr($this->data, 5);
					$data_left -= 5;
					break;
				// unknown (very rare, appears in front of a 'LeaveGame' action)
				case 0x23:
					$this->data = substr($this->data, 11);
					$data_left -= 11;
					break;
				// Forced game end countdown (map is revealed)
				case 0x2F:
					$this->data = substr($this->data, 9);
					$data_left -= 9;
					break;
				// LeaveGame
				case 0x17:
					$this->leaves++;
					$temp = unpack('x1/Vreason/Cplayer_id/Vresult/Vunknown', $this->data);
					$this->players[$temp['player_id']]['time'] = $this->time;
					$this->players[$temp['player_id']]['leave_reason'] = $temp['reason'];
					$this->players[$temp['player_id']]['leave_result'] = $temp['result'];
					$this->data = substr($this->data, 14);
					$data_left -= 14;
					if ($this->leave_unknown) {
						$this->leave_unknown = $temp['unknown'] - $this->leave_unknown;
					}
					$this->game['saver_id'] = $temp['player_id'];
					if ($this->leaves == $this->game['player_count']) {
						$this->game['saver_id'] = $temp['player_id'];
						$this->game['saver_name'] = $this->players[$temp['player_id']]['name'];
					}
					if ($temp['reason'] == 0x01) {
						switch ($temp['result']) {
							case 0x08: $this->game['loser_team'] = $this->players[$temp['player_id']]['team']; break;
							case 0x09: $this->game['winner_team'] = $this->players[$temp['player_id']]['team']; break;
							case 0x0A: $this->game['loser_team'] = 'tie'; $this->game['winner_team'] = 'tie'; break;
						}
					} elseif ($temp['reason'] == 0x0C && $this->game['saver_id']) {
						switch ($temp['result']) {
							case 0x07:
								if ($this->leave_unknown > 0 && $this->continue_game) {
									$this->game['winner_team'] = $this->players[$this->game['saver_id']]['team'];
								} else {
									$this->game['loser_team'] = $this->players[$this->game['saver_id']]['team'];
								}
							break;
							case 0x08: $this->game['loser_team'] = $this->players[$this->game['saver_id']]['team']; break;
							case 0x09: $this->game['winner_team'] = $this->players[$this->game['saver_id']]['team']; break;
							case 0x0B: // this isn't correct according to w3g_format but generally works...
								if ($this->leave_unknown > 0) {
									$this->game['winner_team'] = $this->players[$this->game['saver_id']]['team'];
								}
							break;
						}
					} elseif ($temp['reason'] == 0x0C) {
						switch ($temp['result']) {
							case 0x07: $this->game['loser_team'] = 99; break; // saver
							case 0x08: $this->game['winner_team'] = $this->players[$temp['player_id']]['team']; break;
							case 0x09: $this->game['winner_team'] = 99; break; // saver
							case 0x0A: $this->game['loser_team'] = 'tie'; $this->game['winner_team'] = 'tie'; break;
						}
					}
					$this->leave_unknown = $temp['unknown'];
					break;
				case 0:
					$data_left = 0;
					break;
				default:
					$this->data = substr($this->data, 1);
					$data_left -= 1;
					break;
					//exit('Unhandled replay command block: 0x'.sprintf('%02X', $block_id).' (prev: 0x'.sprintf('%02X', $prev).', time: '.$this->time.') in '.$this->filename);
			}
		}
	}
	
	function hexToStr($hex)
{
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2)
    {
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}

// ACTIONS, the best part...
  function parseactions($actionblock, $data_length) {
  $block_length = 0;

  while ($data_length) {
    if ($block_length) {
      $actionblock = substr($actionblock, $block_length);
    }
	if(strlen($actionblock))
	{
    $temp = unpack('Cplayer_id/Slength', $actionblock);
    $player_id = $temp['player_id'];
    $block_length = $temp['length']+3;
    $data_length -= $block_length;

    $was_deselect = false;
    $was_subupdate = false;

    $n = 3;
	$ablength = strlen($actionblock);
    while ($n < $block_length) {
	
		
        //$prev = $action;
        $action = ord($actionblock{$n});

      switch ($action) {
			// DotA player stats (kill, deaths, creep kills, creep denies)
		case 0x6B:
			
			$n++;	//Skip the action byte
			$n+=5;
			$data = substr($actionblock, $n, 4);
			if($data == 'Data')
			{
				$n++;
				$n+=4;
				
				if( substr($actionblock, $n, 9) == 'GameStart')
				{
					$n+=9;
					$temp['player_id'] = '';
					$temp['text'] = '-- Creeps Spawn --';
					$temp['type'] = 'Start';
					$temp['time'] = $this->time;
					$temp['mode'] = 'System';
					$this->chat[] = $temp;
				}
				else if(substr($actionblock, $n, 4) == 'Hero')
				{
					$n+=4;
					
					$victim = substr($actionblock, $n, 2);
					if(strlen(trim($victim)) == 1)
					{
						$killer = (ord($actionblock{$n+2}));
					}
					else
					{
						$killer = (ord($actionblock{$n+3}));
					}
					$temp['player_id'] = '';
					$temp['killer'] = $killer;
					$temp['victim'] = $victim;
					$temp['type'] = 'Hero';
					$temp['text'] = ' killed ';
					$temp['time'] = $this->time;
					$temp['mode'] = 'System';
					$this->chat[] = $temp;
				}
				else if(substr($actionblock, $n, 7) == 'Courier')
				{
					$n+=7;
					
					$victim = substr($actionblock, $n, 2);
					if(strlen(trim($victim)) == 1)
					{
						$killer = ord($actionblock{$n+2});
					}
					else
					{
						$killer = ord($actionblock{$n+3});
					}
					$temp['player_id'] = '';
					$temp['killer'] = $killer;
					$temp['victim'] = $victim;
					$temp['type'] = 'Courier';
					$temp['text'] = '\'s courier was killed by ';
					$temp['time'] = $this->time;
					$temp['mode'] = 'System';
					$this->chat[] = $temp;
				}
				else if(substr($actionblock, $n, 5) == 'Tower')
				{
					$n+=5;
					$team = substr($actionblock, $n, 1);
					$level = substr($actionblock, $n+1, 1);
					$side = substr($actionblock, $n+2, 1);
					$killer = ord($actionblock{$n+4});
					if($team == '0')
					{
						$team = 'Sentinel';
					}
					else
					{
						$team = 'Scourge';
					}
					
					if($side == '0')
					{
						$side = 'top';
					}
					else if($side == '1')
					{
						$side = 'middle';
					}
					else if($side == '2')
					{
						$side = 'bottom';
					}
					
					$temp['player_id'] = '';
					$temp['killer'] = $killer;
					$temp['type'] = 'Tower';
					$temp['side'] = $side;
					$temp['level'] = $level;
					$temp['team'] = $team;
					if($team == 'Sentinel' && ($killer > 0 && $killer <= 5))
					{
						$temp['text'] = ' denied the ';
					}
					else if($team == 'Scourge' && ($killer <=11 && $killer >= 7))
					{
						$temp['text'] = ' denied the ';
					}
					else
					{
						$temp['text'] = ' killed the ';
					}
					$temp['time'] = $this->time;
					$temp['mode'] = 'System';
					$this->chat[] = $temp;
				}
				else if(substr($actionblock, $n, 3) == 'Rax')
				{
					$n+=3;
					$team = substr($actionblock, $n, 1);
					$side = substr($actionblock, $n+1, 1);
					$type = substr($actionblock, $n+2, 1);
					$killer = ord($actionblock{$n+4});
					if($team == '0')
					{
						$team = 'Sentinel';
					}
					else
					{
						$team = 'Scourge';
					}
					
					if($side == '0')
					{
						$side = 'top';
					}
					else if($side == '1')
					{
						$side = 'middle';
					}
					else if($side == '2')
					{
						$side = 'bottom';
					}
					
					if($type == '0')
					{
						$type = 'melee';
					}
					else
					{
						$type = 'ranged';
					}
					
					$temp['player_id'] = '';
					$temp['killer'] = $killer;
					$temp['type'] = 'Rax';
					$temp['side'] = $side;
					$temp['raxtype'] = $type;
					$temp['team'] = $team;
					if($team == 'Sentinel' && ($killer > 0 && $killer <= 5))
					{
						$temp['text'] = ' denied the ';
					}
					else if($team == 'Scourge' && ($killer <=11 && $killer >= 7))
					{
						$temp['text'] = ' denied the ';
					}
					else
					{
						$temp['text'] = ' killed the ';
					}
					
					$temp['time'] = $this->time;
					$temp['mode'] = 'System';
					$this->chat[] = $temp;
				}
				else if(substr($actionblock, $n, 6) == 'Throne')
				{
					$n+=6;
					$percent = ord($actionblock{$n+1});
					$temp['player_id'] = '';
					$temp['type'] = 'Throne';
					$temp['text'] = 'The Frozen Throne is at '.$percent.' % health';
					$temp['time'] = $this->time;
					$temp['mode'] = 'System';
					$this->chat[] = $temp;
				}
				else if(substr($actionblock, $n, 4) == 'Tree')
				{
					$n+=4;
					$percent = ord($actionblock{$n+1});
					$temp['player_id'] = '';
					$temp['type'] = 'Tree';
					$temp['text'] = 'The World Tree is at '.$percent.' % health';
					$temp['time'] = $this->time;
					$temp['mode'] = 'System';
					$this->chat[] = $temp;
				}
			}
			$n+=2;
			break;

          default:
			$n+=2;
        }
		if(!isset($actionblock{$n}))
		{
			$n = $block_length;
		}
      }
	}
	else
	{
		$n = 3;
		break;
	}
  }
 }

	
	function cleanup() {
		// players time cleanup
		foreach ($this->players as $player) {
			if(isset($player['time']))
			{
				if (!$player['time']) {
					$this->players[$player['player_id']]['time'] = $this->header['length'];
				}
			}
		}
	
		// counting apm
		if ($this->parse_actions) {
			foreach ($this->players as $player_id=>$info) {
				if(isset($this->players[$player_id]['time']))
				{
					if ($this->players[$player_id]['team'] != 12) { // whole team 12 are observers/referees
						$this->players[$player_id]['apm'] = $this->players[$player_id]['actions'] / $this->players[$player_id]['time'] * 60000;
					}
				}
			}
		}
	
		// splitting teams
		foreach ($this->players as $player_id=>$info) {
			if (isset($info['team'])) { // to eliminate zombie-observers caused by Waaagh!TV
				$this->teams[$info['team']][$player_id] = $info;
			}
		}
	}
}


?>
