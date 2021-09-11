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
class ActivatedHero {
    private $Data;
    private $Skills;
    // Used to for evading duplicated actions
    private $lastSkilledTime = 0;
    // Used for limiting skill levels
    private $limitStats = 0;
    
    
    /**
    * Constructor
    * @param mixed $heroData
    */
    function ActivatedHero($heroData) {
        $this->Data = $heroData;
        $this->Skills = array();
    }
    
    /**
    * Add learned skill to hero
    * 
    * @param mixed $skill - Skill Data 
    * @param mixed $time - Time learned ( non converted in miliseconds )
    */
    function setSkill($skill, $time) {
        
        // TODO - Handle duplication / Level limit / Skill limit etc
        // Level limit Common skills A0NR and Aamk
        if($this->limitStats >= 10 && ($skill->getId() == "Aamk" || $skill->getId() == "A0NR")) {
            return;
        }
        
        // Handling duplication
        if($time - $this->lastSkilledTime < DUPLICATE_SKILLING_TIME_LIMIT)
            return;
        
        $this->lastSkilledTime = $time;
        
        // Limit learned skills to 25
        if ( count ( $this->Skills ) >= 25 ) {
            return;    
        }
        
        // Add skill  
        $this->Skills[$time] = $skill;
        
        if($skill->getId() == "Aamk" || $skill->getId() == "A0NR") {
            $this->limitStats++;
        }
    }
    
    /**
    * @return Skills[time in miliseconds] = XML Skill data
    */
    function getSkills() {
        return $this->Skills;
    }
    
    /**
    * Get Hero ID
    * @return String Hero ID
    */
    function getId() {
        return $this->Data->getId();
    }
    
    function getName() {
        return $this->Data->getName();
    }
    
    /**
    * Return XML data for Hero
    * @return data - XML Data for Hero
    */
    function getData() {
        return $this->Data;
    }
    
    /**
    * @return int Level
    */
    function getLevel() {
        return count ( $this->Skills);
    }
}


class PlayerStats {
    private $PID;
      
    public $HeroKills;
    public $Deaths;
    public $CreepKills;
    public $CreepDenies;    
    public $Assists;
    public $EndGold;
    public $Neutrals;
    
    private $DelayedSkills;
    
    public $Inventory;      // Array with 6 elements, for the 6 inventory slots
    
    private $Hero;    // Class ActivatedHero
    
    public $AA_Total;
    public $AA_Hit;
    public $HA_Total;
    public $HA_Hit;
    
    // Dota broadcasts hero levels, so this is used to check for skill duplications and similiar incidents.
    private $levelCap = 1;
    
    /**
    * @desc Constructor
    * @param Player's ID    
    */
    function PlayerStats($PID) {
        $this->PID = $PID;
        $this->Inventory = array();
        $this->Hero = false; 
        
        $this->DelayedSkills = array();
    }
    
    function setAA_Total($var) {
        $this->AA_Total = $var;
    }
    function setAA_Hits($var) {
        $this->AA_Hits = $var;
    }
    function setHA_Total($var) {
        $this->HA_Total = $var;
    }
    function setHA_Hits($var) {
        $this->HA_Hits = $var;
    }
    
    /**
    * 
    * @desc Set Hero
    * @param mixed Hero - ActivatedHero class
    */
    function setHero($hero) {
        $this->Hero = $hero;
    }
    
    /**
    * Return hero data - ActivatedHero class
    */
    function getHero() {
        if(!isset($this->Hero)) {
            return false;
        }
        return $this->Hero;
    }
    
    /**
    * Get the current level cap
    * @returns Integer - Current level cap
    */
    function getLevelCap() {
        return $this->levelCap;
    }
    /**
    * Set the level cap
    * @param $levelCap - Level cap to set
    */
    function setLevelCap($levelCap) {
        $this->levelCap = $levelCap;
    }
    
    /**
    * @return boolean TRUE if hero is set, FALSE otherwise
    */
    function isSetHero() {
        if ( $this->Hero === false) {
            return false;
        }
        return true;
    }
    
    /**
    * Queue up skills, to be added later
    * 
    * @param mixed $skill_data Skill Data
    * @param mixed $time Replay time in miliseconds
    * @param String $heroId Hero ID
    */
    function addDelayedSkill( $skill_data, $time, $heroId ) {
        $this->DelayedSkills[] = array( 'skill_data' => $skill_data, 'time' => $time, 'heroId' => $heroId );
    }
    
    /**
    * Process delayed skills
    * 
    */
    function processDelayedSkills() {
        if ( count( $this->DelayedSkills) > 0 ) {
            
            foreach( $this->DelayedSkills as $element ) {
                // if ( !is_object($this->Hero) ) continue;

                if ( $this->Hero->getId() == $element['heroId'] ) {
                   $this->Hero->setSkill ( $element['skill_data'], $element['time'] );
                }
                // TODO: Otherwise added the skill to the appropriate activated hero
            }
        }
    }
  }
  




///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function convert_bool($value) {
	if (!$value)
		return false;
	
	return true;
}

function convert_speed($value) {
	switch ($value) {
		case 0: $value = 'Slow'; break;
		case 1: $value = 'Normal'; break;
		case 2: $value = 'Fast'; break;
	}
	return $value;
}

function convert_visibility($value) {
	switch ($value) {
		case 0: $value = 'Hide Terrain'; break;
		case 1: $value = 'Map Explored'; break;
		case 2: $value = 'Always Visible'; break;
		case 3: $value = 'Default'; break;
	}
	return $value;
}

function convert_observers($value) {
	switch ($value) {
		case 0: $value = 'No Observers'; break;
		case 2: $value = 'Observers on Defeat'; break;
		case 3: $value = 'Full Observers'; break;
		case 4: $value = 'Referees'; break;
	}
	return $value;
}

function convert_game_type($value) {
	switch ($value) {
		case 0x01: $value = 'Ladder 1vs1/FFA'; break;
		case 0x09: $value = 'Custom game'; break;
		case 0x0D: $value = 'Single player/Local game'; break;
		case 0x20: $value = 'Ladder team game (AT/RT)'; break;
		default: $value = 'unknown';
	}
	return $value;
}

function convert_color($value) {
	switch ($value) {
		case 0: $value = 'red'; break;
		case 1: $value = 'blue'; break;
		case 2: $value = 'teal'; break;
		case 3: $value = 'purple'; break;
		case 4: $value = 'yellow'; break;
		case 5: $value = 'orange'; break;
		case 6: $value = 'green'; break;
		case 7: $value = 'pink'; break;
		case 8: $value = 'gray'; break;
		case 9: $value = 'light-blue'; break;
		case 10: $value = 'dark-green'; break;
		case 11: $value = 'brown'; break;
		case 12: $value = 'observer'; break;
	}
	return $value;
}

function convert_race($value) {
	switch ($value) {
		case 'hpea': case 0x01: case 0x41: $value = 'Human'; break;
		case 'opeo': case 0x02: case 0x42: $value = 'Orc'; break;
		case 'ewsp': case 0x04: case 0x44: $value = 'NightElf'; break;
		case 'uaco': case 0x08: case 0x48: $value = 'Undead'; break;
		case 0x20: case 0x60: $value = 'Random'; break;
		default: $value = 0; // do not change this line
	}
	return $value;
}

function convert_ai($value) {
	switch ($value) {
		case 0x00: $value = "Easy"; break;
		case 0x01: $value = "Normal"; break;
		case 0x02: $value = "Insane"; break;
	}
	return $value;
}

function convert_select_mode($value) {
	switch ($value) {
		case 0x00: $value = "Team & race selectable"; break;
		case 0x01: $value = "Team not selectable"; break;
		case 0x03: $value = "Team & race not selectable"; break;
		case 0x04: $value = "Race fixed to random"; break;
		case 0xCC: $value = "Automated Match Making (ladder)"; break;
	}
	return $value;
}

function convert_chat_mode($value, $player='unknown') {
	switch ($value) {
		case 0x00: $value = 'All'; break;
		case 0x01: $value = 'Allies'; break;
		case 0x02: $value = 'Observers'; break;
		case 0xFE: $value = 'The game has been paused by '.$player.'.'; break;
		case 0xFF: $value = 'The game has been resumed by '.$player.'.'; break;
		default: $value -= 2; // this is for private messages
	}
	return $value;
}

/*
if ($value{0} == 'A') {
    switch ($value) {

      case 'Aamk': $value = 'a_Common:Attribute Bonus'; break;

      case 'A0MF': $value = 'a_Abaddon:Aphotic Shield'; break;
      case 'A0I3': $value = 'a_Abaddon:Death Coil'; break;
      case 'A0MG': $value = 'a_Abaddon:Frostmourne'; break;
      case 'A0NS': $value = 'a_Abaddon:Borrowed Time'; break;

      case 'A0C7': $value = 'a_Axe:Berserker\'s Call'; break;
      case 'A0C5': $value = 'a_Axe:Battle Hunger'; break;
      case 'A0C6': $value = 'a_Axe:Counter Helix'; break;
      case 'A0E2': $value = 'a_Axe:Culling Blade'; break;

      case 'A022': $value = 'a_Anti-Mage:Mana Break'; break;
      case 'AEbl': $value = 'a_Anti-Mage:Blink'; break;
      case 'A0KY': $value = 'a_Anti-Mage:Spell Shield'; break;
      case 'A0E3': $value = 'a_Anti-Mage:Mana Void'; break;

      case 'A02H': $value = 'a_Balanar:Void'; break;
      case 'A08E': $value = 'a_Balanar:Crippling Fear'; break;
      case 'A086': $value = 'a_Balanar:Hunter in the Night'; break;
      case 'A03K': $value = 'a_Balanar:Darkness'; break;

      case 'A03D': $value = 'a_Banehallow:Summon Wolves'; break;
      case 'A02G': $value = 'a_Banehallow:Howl'; break;
      case 'A03E': $value = 'a_Banehallow:Feral Heart'; break;
      case 'A093': $value = 'a_Banehallow:Shapeshift'; break;

      case 'A0ML': $value = 'a_Bara:Charge of Darkness'; break;
      case 'A0ES': $value = 'a_Bara:Empowering Haste'; break;
      case 'A0G5': $value = 'a_Bara:Greater Bash'; break;
      case 'A0G4': $value = 'a_Bara:Nether Strike'; break;

      case 'A0EC': $value = 'a_Bloodseeker:Bloodrage'; break;
      case 'A0LE': $value = 'a_Bloodseeker:Blood Bath'; break;
      case 'A0I8': $value = 'a_Bloodseeker:Strygwyr\'s Thirst'; break;
      case 'A0LH': $value = 'a_Bloodseeker:Rupture'; break;

      case 'A004': $value = 'a_Bounty Hunter:Shuriken Toss'; break;
      case 'A000': $value = 'a_Bounty Hunter:Jinada'; break;
      case 'A07A': $value = 'a_Bounty Hunter:Wind Walk'; break;
      case 'A0B4': $value = 'a_Bounty Hunter:Track'; break;

      case 'A0BH': $value = 'a_Broodmother:Spawn Spiderlings'; break;
      case 'A0BG': $value = 'a_Broodmother:Spin Web'; break;
      case 'A0BK': $value = 'a_Broodmother:Incapacitating Bite'; break;
      case 'A0BP': $value = 'a_Broodmother:Insatiable Hunger'; break;

      case 'A00S': $value = 'a_Centaur:Hoof Stomp'; break;
      case 'A00L': $value = 'a_Centaur:Double Edge'; break;
      case 'A00V': $value = 'a_Centaur:Return'; break;
      case 'A01L': $value = 'a_Centaur:Great Fortitude'; break;

      case 'A055': $value = 'a_Chaos Knight:Chaos Bolt'; break;
      case 'A09F': $value = 'a_Chaos Knight:Blink Strike'; break;
      case 'A03N': $value = 'a_Chaos Knight:Critical Strike'; break;
      case 'A03O': $value = 'a_Chaos Knight:Phantasm'; break;

      case 'A0KM': $value = 'a_Chen:Penitence'; break;
      case 'A0LV': $value = 'a_Chen:Test of Faith'; break;
      case 'A069': $value = 'a_Chen:Holy Persuasion'; break;
      case 'A0LT': $value = 'a_Chen:Hand of God'; break;

      case 'A030': $value = 'a_Clinkz:Strafe'; break;
      case 'AHfa': $value = 'a_Clinkz:Searing Arrows'; break;
      case 'A025': $value = 'a_Clinkz:Wind Walk'; break;
      case 'A04Q': $value = 'a_Clinkz:Death Pact'; break;

      case 'A03F': $value = 'a_Dragon Knight:Breathe Fire'; break;
      case 'A0AR': $value = 'a_Dragon Knight:Dragon Tail'; break;
      case 'A0CL': $value = 'a_Dragon Knight:Dragon Blood'; break;
      case 'A03G': $value = 'a_Dragon Knight:Elder Dragon Form'; break;

      case 'A026': $value = 'a_Drow:Frost Arrows'; break;
      case 'ANsi': $value = 'a_Common:Silence'; break;
      case 'A029': $value = 'a_Drow:Trueshot Aura'; break;
      case 'A056': $value = 'a_Drow:Marksmanship'; break;

      case 'A04V': $value = 'a_Elemental:Enfeeble'; break;
      case 'A0GK': $value = 'a_Elemental:Brain Sap'; break;
      case 'A04Y': $value = 'a_Elemental:Nightmare'; break;
      case 'A02Q': $value = 'a_Elemental:Fiend\'s Grip'; break;

      case 'A0DY': $value = 'a_Enchantress:Impetus'; break;
      case 'A0DX': $value = 'a_Enchantress:Enchant'; break;
      case 'A01B': $value = 'a_Enchantress:Nature\'s Attendants'; break;
      case 'A0DW': $value = 'a_Enchantress:Untouchable'; break;

      case 'A0B3': $value = 'a_Enigma:Malefice'; break;
      case 'A0B7': $value = 'a_Enigma:Conversion'; break;
      case 'A0B1': $value = 'a_Enigma:Midnight Pulse'; break;
      case 'A0BY': $value = 'a_Enigma:Black Hole'; break;

      case 'A06Q': $value = 'a_Furion:Sprout'; break;
      case 'A01O': $value = 'a_Furion:Teleportation'; break;
      case 'AEfn': $value = 'a_Furion:Force of Nature'; break;
      case 'A07X': $value = 'a_Furion:Wrath of Nature'; break;

      case 'A0JL': $value = 'a_Invoker:Quas'; break;
      case 'A0JM': $value = 'a_Invoker:Wex'; break;
      case 'A0JK': $value = 'a_Invoker:Exort'; break;
      case 'A0IY': $value = 'a_Invoker:Invoke'; break;

      case 'A05G': $value = 'a_Juggernaut:Blade Fury'; break;
      case 'A047': $value = 'a_Juggernaut:Healing Ward'; break;
      case 'A00K': $value = 'a_Juggernaut:Blade Dance'; break;
      case 'A0M1': $value = 'a_Juggernaut:Omnislash'; break;

      case 'A085': $value = 'a_Keeper:Illuminate'; break;
      case 'A07Y': $value = 'a_Keeper:Mana Leak'; break;
      case 'A07N': $value = 'a_Keeper:Chakra Magic'; break;
      case 'A0MO': $value = 'a_Keeper:Ignis Fatuus'; break;
     
      case 'A02C': $value = 'a_Krob:Witchcraft'; break;
      case 'A073': $value = 'a_Krob:Exorcism0'; break;
      case 'A03J': $value = 'a_Krob:Exorcism1'; break;
      case 'A04J': $value = 'a_Krob:Exorcism2'; break;
      case 'A04M': $value = 'a_Krob:Exorcism3'; break;
      case 'A04N': $value = 'a_Krob:Exorcism4'; break;
      case 'A02M': $value = 'a_Krob:Carrion Swarm0'; break;
      case 'A06N': $value = 'a_Krob:Carrion Swarm1'; break;
      case 'A072': $value = 'a_Krob:Carrion Swarm2'; break; 
      case 'A074': $value = 'a_Krob:Carrion Swarm3'; break;  
      case 'A078': $value = 'a_Krob:Carrion Swarm4'; break; 
      case 'ANsi': $value = 'a_Common:Silence0'; break;
      case 'A07H': $value = 'a_Krob:Silence1'; break;
      case 'A07I': $value = 'a_Krob:Silence2'; break;
      case 'A07J': $value = 'a_Krob:Silence3'; break; 
      case 'A07M': $value = 'a_Krob:Silence4'; break; 

      case 'AHtb': $value = 'a_Common:Storm Bolt'; break;
      case 'AUav': $value = 'a_Leoric:Vampiric Aura'; break;
      case 'AOcr': $value = 'a_Leoric:CriticaIStrike'; break;
      case 'A01Y': $value = 'a_Leoric:Reincarnation'; break;

      case 'A06W': $value = 'a_Lesh:Split Earth'; break;
      case 'A035': $value = 'a_Lesh:Diabolic Edict'; break;
      case 'A06V': $value = 'a_Lesh:Lightning Storm'; break;
      case 'A06X': $value = 'a_Lesh:Pulse Nova'; break;

      case 'A046': $value = 'a_Levi:Gush'; break;
      case 'A04E': $value = 'a_Levi:Kraken Shell'; break;
      case 'A044': $value = 'a_Levi:Anchor Smash'; break;
      case 'A03Z': $value = 'a_Levi:Ravage'; break;

      case 'A07F': $value = 'a_Lich:Frost Nova'; break;
      case 'A08R': $value = 'a_Lich:Frost Armor'; break;
      case 'A053': $value = 'a_Lich:Dark Ritual'; break;
      case 'A05T': $value = 'a_Lich:Chain Frost'; break;

      case 'A02J': $value = 'a_Lion:Impale'; break;
      case 'A0MN': $value = 'a_Common:Voodoo'; break;
      case 'A02N': $value = 'a_Lion:Mana Drain'; break;
      case 'A095': $value = 'a_Lion:Finger of Death'; break;

      case 'A05Y': $value = 'a_Lucy:Devour'; break;
      case 'A0FE': $value = 'a_Lucy:Scorched Earth'; break;
      case 'A094': $value = 'a_Lucy:LVL Death'; break;
      case 'A0MU': $value = 'a_Lucy:Doom'; break;

      case 'A02S': $value = 'a_Magnus:Shockwave'; break;
      case 'A037': $value = 'a_Magnus:Empower'; break;
      case 'A024': $value = 'a_Magnus:Mighty Swing'; break;
      case 'A06F': $value = 'a_Magnus:Reverse Polarity'; break;

      case 'A01D': $value = 'a_Maiden:Frost Nova'; break;
      case 'A04C': $value = 'a_Maiden:Frostbite'; break;
      case 'AHab': $value = 'a_Maiden:Brilliance Aura'; break;
      case 'A03R': $value = 'a_Maiden:Freezing Field'; break;

      case 'A012': $value = 'a_Medusa:Split Shot'; break;
      case 'A00Y': $value = 'a_Common:Chain Lightning:'; break;
      case 'ANms': $value = 'a_Medusa:Mana Shield'; break;
	  case 'A0MP': $value = 'a_Medusa:Mana Shield'; break;
      case 'A02V': $value = 'a_Medusa:Purge'; break;

      case 'A0JQ': $value = 'a_Naix:Feast'; break;
      case 'A01E': $value = 'a_Naix:Poison Sting'; break;
      case 'A06Y': $value = 'a_Naix:Anabolic Frenzy'; break;
      case 'A028': $value = 'a_Naix:Rage'; break;

      case 'A05V': $value = 'a_Necro:Death Pulse'; break;
      case 'A0MC': $value = 'a_Necro:Diffusion Aura'; break;
      case 'A060': $value = 'a_Necro:Sadist'; break;
      case 'A067': $value = 'a_Necro:Reaper\'s Scythe'; break;

      case 'A09K': $value = 'a_Nerub:Impale'; break;
      case 'A02K': $value = 'a_Nerub:Mana Burn'; break;
      case 'A02L': $value = 'a_Nerub:Spiked Carapace'; break;
      case 'A09U': $value = 'a_Nerub:Vendetta'; break;

      case 'A0EY': $value = 'a_Nevermore:Shadowraze'; break;
      case 'A0BR': $value = 'a_Nevermore:Necromastery'; break;
      case 'A0FU': $value = 'a_Nevermore:Presence of the Dark Lord'; break;
      case 'A0HE': $value = 'a_Nevermore:Requiem of Souls'; break;
      case 'A0AJ': $value = 'a_Common:Special Attribute Bonus 2'; break;

      case 'AEsh': $value = 'a_Common:Shadow Strike'; break;
      case 'A0K9': $value = 'a_Common:Blink Strike'; break;
      case 'A03P': $value = 'a_PA:Blur'; break;
      case 'A03Q': $value = 'a_PA:Coup de Grace'; break;

      case 'A06I': $value = 'a_Pudge:Meat Hook'; break;
      case 'A06K': $value = 'a_Pudge:Rot'; break;
      case 'A06D': $value = 'a_Pudge:Flesh Heap'; break;
      case 'A0FL': $value = 'a_Pudge:Dismember'; break;

      case 'A0MT': $value = 'a_Pugna:Nether Blast'; break;
      case 'A0CE': $value = 'a_Pugna:Decrepify'; break;
      case 'A09D': $value = 'a_Pugna:Nether Ward'; break;
      case 'A0CC': $value = 'a_Pugna:Life Drain'; break;

      case 'AEsh': $value = 'a_Common:Shadow Strike'; break;
      case 'A0ME': $value = 'a_Common:Blink'; break;
      case 'A04A': $value = 'a_Queen:Scream of Pain'; break;
      case 'A00O': $value = 'a_Queen:Sonic Wave'; break;

      case 'A05D': $value = 'a_Common:Frenzy'; break;
      case 'A00Y': $value = 'a_Common:Chain Lightning'; break;
      case 'A00N': $value = 'a_Razor:Unholy Fervor'; break;
      case 'A04B': $value = 'a_Razor:Storm Seeker'; break;

      case 'A0FW': $value = 'a_Rigwarl:Viscous Nasal Goo'; break;
      case 'A0GP': $value = 'a_Rigwarl:Quill Spray'; break;
      case 'A0M3': $value = 'a_Rigwarl:Bristleback'; break;
      case 'A0FV': $value = 'a_Rigwarl:Warpath'; break;

      case 'A06O': $value = 'a_Sand King:Burrowstrike'; break;
      case 'A0H0': $value = 'a_Sand King:Sand Storm'; break;
      case 'A0FA': $value = 'a_Sand King:Caustic Finale'; break;
      case 'A06R': $value = 'a_Sand King:Epicenter'; break;

      case 'A05C': $value = 'a_Slardar:Sprint'; break;
      case 'A01W': $value = 'a_Slardar:War Stomp'; break;
      case 'A0JJ': $value = 'a_Slardar:Bash'; break;
      case 'A034': $value = 'a_Slardar:Amplify Damage'; break;

      case 'A04L': $value = 'a_Terrorblade:Soul Steal'; break;
      case 'A08Q': $value = 'a_Terrorblade:Conjure Image'; break;
      case 'A0MV': $value = 'a_Terrorblade:Metamorphosis'; break;
      case 'A07Q': $value = 'a_Terrorblade:Sunder'; break;

      case 'A08X': $value = 'a_Visage:Grave Chill'; break;
      case 'A0C4': $value = 'a_Visage:Soul Assumption'; break;
      case 'A0MD': $value = 'a_Visage:Gravekeeper\'s Cloak'; break;
      case 'A07K': $value = 'a_Visage:Raise Revenants'; break;

      case 'AEsh': $value = 'a_Common:Shadow Strike'; break;
      case 'A0MY': $value = 'a_Venom:Poison Sting'; break;
      case 'A0MS': $value = 'a_Venom:Plague Ward'; break;
      case 'A013': $value = 'a_Venom:Poison Nova'; break;

      case 'A0LK': $value = 'a_Void:Time Walk'; break;
      case 'A0CZ': $value = 'a_Void:Backtrack'; break;
      case 'A081': $value = 'a_Void:Time Lock'; break;
      case 'A0J1': $value = 'a_Void:Chronosphere'; break;

      case 'A00T': $value = 'a_Weaver:Watcher'; break;
      case 'A0CA': $value = 'a_Weaver:Shukuchi'; break;
      case 'A0CG': $value = 'a_Weaver:Geminate Attack'; break;
      case 'A0CT': $value = 'a_Weaver:Time Lapse'; break;

      case 'A0M0': $value = 'a_Tauren:Fissure'; break;
      case 'A0DL': $value = 'a_Tauren:Enchant Totem'; break;
      case 'A0DJ': $value = 'a_Tauren:Aftershock'; break;
      case 'A0DH': $value = 'a_Tauren:Echo Slam'; break;

      case 'A01F': $value = 'a_Lina:Dragon Slave'; break;
      case 'A027': $value = 'a_Lina:Light Strike Array'; break;
      case 'A001': $value = 'a_Lina:Ultimate'; break;
      case 'A01P': $value = 'a_Lina:Laguna Blade'; break;

      case 'A042': $value = 'a_Luna:Lucent Beam'; break;
      case 'A041': $value = 'a_Luna:Moon Glaive'; break;
      case 'A062': $value = 'a_Luna:Lunar Blessing'; break;
      case 'A054': $value = 'a_Luna:Eclipse'; break;

      case 'A04W': $value = 'a_Ogre:Fireblast'; break;
      case 'A011': $value = 'a_Ogre:Ignite'; break;
      case 'A083': $value = 'a_Ogre:Bloodlust'; break;
      case 'A088': $value = 'a_Ogre:Multi Cast'; break;
      case '????': $value = 'a_Ogre:Unknown Ogre Related1'; break; // A089
      case '????': $value = 'a_Ogre:Unknown Ogre Related2'; break; // A007

      case 'A0FN': $value = 'a_Morph:Waveform'; break; 
      case 'A0G6': $value = 'a_Morph:Morph Attack'; break; 
      case 'A0KX': $value = 'a_Morph:Morph'; break;
      case 'A0G8': $value = 'a_Morph:Adapt'; break; // was A0F4
      case 'A0NR': $value = 'a_Common:Special Attribute Bonus 1'; break;

      case 'A063': $value = 'a_Naga:Mirror Image'; break;
      case 'A0BA': $value = 'a_Naga:Ensnare'; break;
      case 'A00E': $value = 'a_Naga:Comical Strike'; break;
      case 'A07U': $value = 'a_Naga:Song of the Siren'; break;

      case 'A08N': $value = 'a_Omni Knight:Purification'; break;
      case 'A08V': $value = 'a_Omni Knight:Repel'; break;
      case 'A06A': $value = 'a_Omni Knight:Degen Aura'; break;
      case 'A0ER': $value = 'a_Omni Knight:Guardian Angel'; break;

      case 'A06M': $value = 'a_Panda:Thunder Clap'; break;
      case 'Acdh': $value = 'a_Panda:Drunken Haze'; break;
      case 'A0MX': $value = 'a_Panda:Drunken Brawler'; break;
      case 'A0MQ': $value = 'a_Panda:Primal Split'; break;

      case 'A0DA': $value = 'a_Lancer:Spirit Lance'; break;
      case 'A0D7': $value = 'a_Lancer:Dopplewalk'; break;
      case 'A0DB': $value = 'a_Lancer:Juxtapose'; break;
      case 'A0D9': $value = 'a_Lancer:Phantom Edge'; break;

      case 'A010': $value = 'a_Rhasta:Forked Lightning'; break;
      case 'A0MN': $value = 'a_Common:Voodoo'; break;
      case 'A00P': $value = 'a_Rhasta:Hero Shackles'; break;
      case 'A00H': $value = 'a_Rhasta:Mass Serpent Ward'; break;
      case 'A0A1': $value = 'a_Rhasta:Mass Serpent Ward (aghanim related)'; break;

      case 'A0E6': $value = 'a_SA:Smoke Screen'; break;
      case 'A0K9': $value = 'a_Common:Blink Strike'; break;
      case 'A0DZ': $value = 'a_SA:Backstab'; break;
      case 'A00J': $value = 'a_SA:Permanent Invisibility'; break;

      case 'A0KD': $value = 'a_Silencer:Curse of the Silent'; break;
      case 'A0LZ': $value = 'a_Silencer:Glaives of Wisdom'; break;
      case 'A0LR': $value = 'a_Silencer:Last Word'; break;
      case 'A0L3': $value = 'a_Silencer:Global Silence'; break;

      case 'A02A': $value = 'a_Silk:Magic Missile'; break;
      case 'A0AP': $value = 'a_Silk:Terror'; break;
      case 'A045': $value = 'a_Silk:Command Aura'; break;
      case 'A00G': $value = 'a_Silk:Nether Swap'; break;

      case 'A020': $value = 'a_Zeus:Arc Lightning'; break;
      case 'A006': $value = 'a_Zeus:Lightning Bolt'; break;
      case 'A0N5': $value = 'a_Zeus:Static Field'; break;
      case 'A07C': $value = 'a_Zeus:Thundergod\'s Wrath'; break;

      case 'AHtb': $value = 'a_Common:Storm Bolt'; break;
      case 'A01K': $value = 'a_Sven:Great Cleave'; break;
      case 'A01M': $value = 'a_Sven:Toughness Aura'; break;
      case 'A01H': $value = 'a_Sven:God\'s Strength'; break;

      case 'A0BE': $value = 'a_Troll:Berserker Rage'; break;
      case 'A0BC': $value = 'a_Troll:Blind'; break;
      case 'A0BD': $value = 'a_Troll:Fervor'; break;
      case 'A0BB': $value = 'a_Troll:Rampage'; break;

      case 'A03Y': $value = 'a_Ursa:Earthshock'; break;
      case 'A059': $value = 'a_Ursa:Overpower'; break;
      case 'ANic': $value = 'a_Ursa:Fury Swipes'; break;
      case 'A0LC': $value = 'a_Ursa:Enrage'; break;

      case 'A05J': $value = 'a_Techies:Land Mines'; break;
      case 'A06H': $value = 'a_Techies:Stasis Trap'; break;
      case 'A06B': $value = 'a_Techies:Suicide Squad, Attack!'; break;
      case 'A0AK': $value = 'a_Techies:Remote Mines'; break;

      case 'A049': $value = 'a_Tinker:Laser'; break;
      case 'A05E': $value = 'a_Tinker:Heat Seeking Missile'; break;
      case 'A0BQ': $value = 'a_Tinker:March of the Machines'; break;
      case 'A065': $value = 'a_Tinker:Rearm'; break;

      case 'A0LL': $value = 'a_Tiny:Avalanche'; break;
      case 'A0BZ': $value = 'a_Tiny:Toss'; break;
      case 'A0BU': $value = 'a_Tiny:Craggy Exterior'; break;
      case 'A0CY': $value = 'a_Tiny:Grow'; break;

      case 'A01Z': $value = 'a_Trellen:Nature\'s Guise'; break;
      case 'A01V': $value = 'a_Trellen:Eyes in the Forest'; break;
      case 'A01U': $value = 'a_Trellen:Living Armor'; break;
      case 'A07Z': $value = 'a_Trellen:Overgrowth'; break;

      case 'A064': $value = 'a_Sniper:ScatterShot'; break;
      case 'A03S': $value = 'a_Sniper:Headshot'; break;
      case 'A03U': $value = 'a_Sniper:Take Aim'; break;
      case 'A04P': $value = 'a_Sniper:Assassinate'; break;

      case 'A05D': $value = 'a_Common:Frenzy'; break;
      case 'A09V': $value = 'a_Viper:Poison Attack'; break;
      case 'A0MM': $value = 'a_Viper:Corrosive Skin'; break;
      case 'A080': $value = 'a_Viper:Viper Strike'; break;

      case 'A0A5': $value = 'a_Syl:Summon Spirit Bear'; break;
      case 'A0AA': $value = 'a_Syl:Rabid'; break;
      case 'A0A8': $value = 'a_Syl:Synergy'; break;
      case 'A0AG': $value = 'a_Syl:True Form'; break;

      case 'A0HW': $value = 'a_Mercurial:Spectral dagger'; break;
      case 'A0FX': $value = 'a_Mercurial:Desolate'; break; // check A0NF?
      case 'A0NA': $value = 'a_Mercurial:Dispersion'; break;
      case 'A0H9': $value = 'a_Mercurial:Global Haunt'; break;

      case 'A0NM': $value = 'a_Voljin:Paralizing Cask'; break;
      case 'A0NE': $value = 'a_Voljin:Voodoo Restoration'; break;
      case 'A0NO': $value = 'a_Voljin:Maledict'; break;
      case 'A0NT': $value = 'a_Voljin:Death Ward'; break;

      case 'A0O7': $value = 'a_Jakiro:Dual Breath'; break;
      case 'A0O6': $value = 'a_Jakiro:Ice Path'; break;
      case 'A0O8': $value = 'a_Jakiro:Auto Fire'; break;
      case 'A0O5': $value = 'a_Jakiro:Macropyre'; break;

      case 'A0O1': $value = 'a_BeastMaster:Wild Axes'; break;
      case 'A0OO': $value = 'a_BeastMaster:Call of the Wild'; break;
      case 'A0O0': $value = 'a_BeastMaster:Beast Rage'; break;
      case 'A0O2': $value = 'a_BeastMaster:Primal Roar'; break;

      case 'A0OI': $value = 'a_Harbinger:Arcane Orb'; break;
      case 'A0OJ': $value = 'a_Harbinger:Astral Imprisonment'; break;
      case 'A0OG': $value = 'a_Harbinger:Essence Aura'; break;
      case 'A0OK': $value = 'a_Harbinger:Sanity Eclipse'; break;
    
      case 'A0IL': $value = 'a_Alchemist:Acid Spray'; break; 
      case 'A0J6': $value = 'a_Alchemist:Unstable Concoction'; break; 
      case 'A0O3': $value = 'a_Alchemist:Goblin Greed'; break; 
      case 'ANcr': $value = 'a_Alchemist:Chemical Rage'; break; 

      case 'A0AS': $value = 'a_Warlock:Fatal Bonds'; break; 
      case 'A0J5': $value = 'a_Warlock:Shadow Word'; break; 
      case 'A06P': $value = 'a_Warlock:Upheaval'; break; 
      case 'S008': $value = 'a_Warlock:Rain of Chaos'; break; 


      case 'A0AJ': $value = 'a_Common:Special Attribute Bonus 2'; break;



    }
  }

  // items
  if ($value{1} != '_') {
    switch ($value) {
//Listed in order of where the shops are on sent (counter-clockwise)
//1
      case 'plcl': $value = 'i_Lesser Clarity Potion|50'; break;
      case 'sor8': $value = 'i_Flask of Sapphire Water|100'; break;
      case 'silk': $value = 'i_Ancient Tango of Essifation|80'; break;
      case 'sman': $value = 'i_Animal Courier|225'; break;
      case 'sor7': $value = 'i_Observer Wards|215'; break;
      case 'tgrh': $value = 'i_Sentry Wards|375'; break;
      case 'stwp': $value = 'i_Scroll of Town Portal|135'; break;
      case 'rej4': $value = 'i_Stout Shield |300'; break;
      case 'I00G': $value = 'i_Empty Bottle |700'; break;
//2
      case 'jdrn': $value = 'i_Mask of Death|900'; break;
      case 'tkno': $value = 'i_Ring of Regeneration|375'; break;
      case 'desc': $value = 'i_Kelen\'s Dagger of Escape|2150'; break;
      case 'shhn': $value = 'i_Sobi Mask|325'; break;
      case 'stre': $value = 'i_Gloves of Haste|610'; break;
      case 'tgxp': $value = 'i_Boots of Speed|500'; break;
      case 'oflg': $value = 'i_Gem of True Sight|750'; break;
      case 'gvsm': $value = 'i_Ultimate Orb|2300'; break;
//3
      case 'stwa': $value = 'i_Gauntlets of Ogre Strength|150'; break;
      case 'shdt': $value = 'i_Slippers of Agility|150'; break;
      case 'sbok': $value = 'i_Mantle of Intelligence|150'; break;
      case 'iwbr': $value = 'i_Ironwood Branch|57'; break;
      case 'bgst': $value = 'i_Belt of Giant Strength|450'; break;
      case 'belv': $value = 'i_Boots of Elvenskin|450'; break;
      case 'ciri': $value = 'i_Robe of the Magi|450'; break;
      case 'tbar': $value = 'i_Circlet of Nobility|185'; break;
      case 'sksh': $value = 'i_Ogre Axe|1000'; break;
      case 'tmmt': $value = 'i_Blade of Alacrity|1000'; break;
      case 'shtm': $value = 'i_Staff of Wizardry|1000'; break;
//4
      case 'tmsc': $value = 'i_Blades of Attack|650'; break;
      case 'sor2': $value = 'i_Broadsword|1200'; break;
      case 'whwd': $value = 'i_Quarterstaff|1150'; break;
      case 'sora': $value = 'i_Claymore|1400'; break;
      case 'flag': $value = 'i_Mithril Hammer|1610'; break;
      case 'modt': $value = 'i_Ring of Protection|175'; break;
      case 'tbsm': $value = 'i_Chainmail|620'; break;
      case 'ram3': $value = 'i_Plate Mail|1400'; break;
      case 'scul': $value = 'i_Helm of Iron Will|950'; break;
      case 'sor1': $value = 'i_Planeswalker\'s Cloak|650'; break;
//5
      case 'rman': $value = 'i_Recipe Buriza-do Kyanon|750'; break;
      case 'odef': $value = 'i_Recipe Monkey King Bar|1650'; break;
      case 'clsd': $value = 'i_Recipe Radiance|1525'; break;
      case 'rag1': $value = 'i_Recipe Heart of Tarrasque|1300'; break;
      case 'tsct': $value = 'i_Recipe Satanic|1100'; break;
      case 'lmbr': $value = 'i_Recipe Eye of Skadi|1250'; break;
      case 'fgfh': $value = 'i_Recipe The Butterfly|1400'; break;
      case 'rat9': $value = 'i_Recipe Refresher Orb|1875'; break;
      case 'evtl': $value = 'i_Recipe Guinsoo\'s Scythe of Vyse|450'; break;
//6
      case 'wlsd': $value = 'i_Recipe Sange and Yasha|1000'; break;
      case 'totw': $value = 'i_Recipe Stygian Desolator|1200'; break;
      case 'rre2': $value = 'i_Recipe Crystalys|500'; break;
      case 'tdx2': $value = 'i_Recipe Black King Bar|1600'; break;
      case 'rde1': $value = 'i_Recipe Manta Style|1400'; break;
      case 'tst2': $value = 'i_Recipe Aegis of the Immortal|2000'; break;
      case 'rde3': $value = 'i_Recipe Lothar\'s Edge|1400'; break;
      case 'rhe3': $value = 'i_Recipe Dagon|1350'; break;
      case 'ajen': $value = 'i_Recipe Necronomicon|1300'; break;
      case 'hslv': $value = 'i_Recipe Linken\'s Sphere|1425'; break;
//7
      case 'I003': $value = 'i_Recipe Yasha|600'; break;
      case 'afac': $value = 'i_Recipe Sange|600'; break;
      case 'rhe1': $value = 'i_Recipe Cranium Basher|1460'; break;
      case 'rwat': $value = 'i_Recipe Blade Mail|500'; break;
      case 'moon': $value = 'i_Recipe Maelstrom|1300'; break;
      case 'manh': $value = 'i_Recipe Diffusal Blade|1550'; break;
      case 'pmna': $value = 'i_Recipe Mask of Madness|1050'; break;
      case 'shar': $value = 'i_Recipe Mekansm|900'; break;
//8
      case 'ssil': $value = 'i_Recipe Headdress of Rejuvenation|225'; break;
      case 'rin1': $value = 'i_Recipe Netherezim Buckler|200'; break;
      case 'rspd': $value = 'i_Recipe Boots of Travel|2200'; break;
      case 'dsum': $value = 'i_Recipe Power Treads|420'; break;
      case 'pghe': $value = 'i_Recipe Hand of Midas|1000'; break;
      case 'rres': $value = 'i_Recipe Bracer|175'; break;
      case 'sres': $value = 'i_Recipe Wraith Band|150'; break;
      case 'kpin': $value = 'i_Recipe Null Talisman|175'; break;
//Secret Shop
      case 'srtl': $value = 'i_Demon Edge|2600'; break;
      case 'srbd': $value = 'i_Eaglehorn|3400'; break;
      case 'rump': $value = 'i_Messerschmidt\'s Reaver|3200'; break;
      case 'shrs': $value = 'i_Sacred Relic|3800'; break;
      case 'sprn': $value = 'i_Hyperstone|2450'; break;
      case 'rej6': $value = 'i_Ring of Health|875'; break;
      case 'shcw': $value = 'i_Void Stone|900'; break;
      case 'ram2': $value = 'i_Mystic Staff|2900'; break;
      case 'pgin': $value = 'i_Energy Booster|1000'; break;
      case 'hbth': $value = 'i_Point Booster|1200'; break;
      case 'oli2': $value = 'i_Vitality Booster|1100'; break;

      case 'gfor': $value = 'i_code:gfor - Unknown if anyone has any idea of what is it|0'; break; // gfor

    }
  }
  return $value;
}
*/
function convert_action($value) {
  switch ($value) {
    case 'rightclick': $value = 'Right click'; break;
    case 'select': $value = 'Select / deselect'; break;
    case 'selecthotkey': $value = 'Select group hotkey'; break;
    case 'assignhotkey': $value = 'Assign group hotkey'; break;
    case 'ability': $value = 'Use ability'; break;
    case 'basic': $value = 'Basic commands'; break;
    case 'buildtrain': $value = 'Build / train'; break;
    case 'buildmenu': $value = 'Enter build submenu'; break;
    case 'heromenu': $value = 'Enter hero\'s abilities submenu'; break;
    case 'subgroup': $value = 'Select subgroup'; break;
    case 'item': $value = 'Give item / drop item'; break;
    case 'removeunit': $value = 'Remove unit from queue'; break;
    case 'esc': $value = 'ESC pressed'; break;
  }
  return $value;
}

function convert_time($value) {
	$output = sprintf('%02d', intval($value/60000)).':';
	$value = $value%60000;
	$output .= sprintf('%02d', intval($value/1000));
	
	return $output;
}

function convert_yesno($value) {
	if (!$value)
		return 'No';
	
	return 'Yes';
}
 function convert_itemid($value) {
  // ignore numeric Item IDs (0x41 - ASCII A, 0x7A - ASCII z)
  if (ord($value{0}) < 0x41 || ord($value{0}) > 0x7A) {
    return 0;
  }

  // you can change the names but not the prefixes (h_, u_, a_, etc.)
  // Human


  // Human heroes
  else if ($value{0} == 'H') {
    switch ($value) {
      case 'Hvsh': $value = 'h_Bloodseeker'; break;
      case 'H00V': $value = 'h_Medusa'; break;
      case 'H00H': $value = 'h_Pugna'; break;
      case 'H008': $value = 'h_Rigwarl'; break;
      case 'Hjai': $value = 'h_Maiden'; break;
      case 'H000': $value = 'h_Centuar'; break;
      case 'Hlgr': $value = 'h_Dragon Knight'; break;
      case 'Hblm': $value = 'h_Keeper'; break;
      case 'H004': $value = 'h_Lina'; break;
      case 'Hmkg': $value = 'h_Ogre'; break;
      case 'HC49': $value = 'h_Naga'; break;
      case 'Harf': $value = 'h_Omni Knight'; break;
      case 'HC92': $value = 'h_SA'; break;
      case 'Hvwd': $value = 'h_Silk'; break;
      case 'Hmbr': $value = 'h_Zues'; break;
      case 'Hpal': $value = 'h_Invoker'; break;
      case 'H001': $value = 'h_Sven'; break;
      case 'Huth': $value = 'h_Ursa'; break;
      case 'H00K': $value = 'h_Techies'; break;
      case 'Hamg': $value = 'h_Trellen'; break;
      case 'Hamg': $value = 'h_Trellen'; break;
      case 'H00A': $value = 'h_Chen'; break;
	//new hero
	case 'H00D': $value = 'h_Beast Master'; break;
	case 'H00I' : $value = 'h_Geomancer'; break;
	//unknown hero id
	default: $value='h_unknown hero id : '.$value;break;

    }
  }

  // Night Elf heroes
  else if ($value{0} == 'E') {
    switch ($value) {
      case 'E004': $value = 'h_Clinkz'; break;
      case 'EC45': $value = 'h_Void'; break;
      case 'Ekee': $value = 'h_Lesh'; break;
      case 'Ewar': $value = 'h_PA'; break;
      case 'E002': $value = 'h_Razor'; break;
      case 'Eevi': $value = 'h_Terrorblade'; break;
      case 'EC57': $value = 'h_Veno'; break;
      case 'EC77': $value = 'h_Viper'; break;
      case 'Edem': $value = 'h_Anti-Mage'; break;
      case 'Emoo': $value = 'h_Enchantress'; break;
      case 'Emns': $value = 'h_Furion'; break;
      case 'E005': $value = 'h_Luna'; break;
	//new hero
	case 'E01C': $value = 'h_Warlock'; break;
	case 'E01A': $value = 'h_Witch Doctor';break;
	case 'E01B': $value = 'h_Spectre'; break;
	case 'E00P': $value = 'h_Twin Head Dragon'; break;
	//unknown hero id
	default: $value='h_unknown hero id : '.$value;break;
    }
  }

  // Orc heroes
  else if ($value{0} == 'O') {
    switch ($value) {
      case 'Oshd': $value = 'h_Elemental'; break;
      case 'Opgh': $value = 'h_Axe'; break;
      case 'O00J': $value = 'h_Bara'; break;
      case 'Ofar': $value = 'h_Levi'; break;
      case 'Otch': $value = 'h_Tauren'; break;
      case 'O00P': $value = 'h_Morph'; break;
      case 'Ogrh': $value = 'h_Lancer'; break;
      case 'Orkn': $value = 'h_Rhasta'; break;
	//unknown hero id
	default: $value='h_unknown hero id : '.$value;break;
    }
  }

  // Undead heroes
  else if ($value{0} == 'U') {
    switch ($value) {
      case 'Udre': $value = 'h_Balanar'; break;
      case 'U006': $value = 'h_Broodmother'; break;
      case 'U00A': $value = 'h_Chaos Knight'; break;
      case 'UC42': $value = 'h_Lucy'; break;
      case 'UC76': $value = 'h_Krob'; break;
      case 'Ulic': $value = 'h_Lich'; break;
      case 'U008': $value = 'h_Banehallow'; break;
      case 'UC18': $value = 'h_Lion'; break;
      case 'U007': $value = 'h_Naix'; break;
      case 'U00K': $value = 'h_Sand King'; break;
      case 'UC11': $value = 'h_Magnus'; break;
      case 'UC60': $value = 'h_Visage'; break;
      case 'U00E': $value = 'h_Necro'; break;
      case 'U000': $value = 'h_Nerub'; break;
      case 'U00F': $value = 'h_Pudge'; break;
      case 'UC01': $value = 'h_Queen'; break;
      case 'UC91': $value = 'h_Slardar'; break;
      case 'Ubal': $value = 'h_Weaver'; break;
      case 'Uktl': $value = 'h_Enigma'; break;
      case 'Ucrl': $value = 'h_Tiny'; break;
      case 'Usyl': $value = 'h_Sniper'; break;
      case 'Udea': $value = 'h_Abaddon'; break;
	//new hero
	case 'U00P': $value = 'h_Obsidian'; break;
	//unknown hero id
	default: $value = 'i_unknown hero id : '.$value;break;
    }
  }

  // neutral heroes
  else if ($value{0} == 'N') {
    switch ($value) {
      case 'Npbm': $value = 'h_Panda'; break;
      case 'NC00': $value = 'h_Leoric'; break;
      case 'Nfir': $value = 'h_Nevermore'; break;
      case 'Naka': $value = 'h_Bounty Hunter'; break;
      case 'Nbrn': $value = 'h_Drow'; break;
      case 'Nbbc': $value = 'h_Juggernaut'; break;
      case 'N01A': $value = 'h_Silencer'; break;
      case 'N01O': $value = 'h_Syl'; break;
      case 'N016': $value = 'h_Troll'; break;
      case 'Ntin': $value = 'h_Tinker'; break;
	//new hero
	case 'N01I': $value = 'h_Alchemist';break;
	case 'N01V': $value = 'h_Potm';break;
	case 'N01W' : $value = 'h_Shadow Priest';break;
        case 'N00R' : $value = 'h_Pit Lord';break;
	//unknown hero id
	default: $value='h_unknown hero id : '.$value;break;

    }
  }

  // hero abilities
  // WARNING: the names of the heroes must be exactly the same like above
  else if ($value{0} == 'A') {
    switch ($value) {

      case 'Aamk': $value = 'a_Common:Attribute Bonus'; break;

      case 'A0MF': $value = 'a_Abaddon:Aphotic Shield'; break;
      case 'A0MI': $value = 'a_Abaddon:Mark of the Abyss'; break;
      case 'A0MG': $value = 'a_Abaddon:Frostmourne'; break;
      case 'A0MK': $value = 'a_Abaddon:Borrowed Time'; break;
      case 'A0NS': $value = 'a_Abaddon:Borrowed Time'; break;
      case 'A0I3': $value = 'a_Abaddon:Death Coil'; break;

      case 'A0C7': $value = 'a_Axe:Berserker\'s Call'; break;
      case 'A0I6': $value = 'a_Axe:Berserker\'s Call'; break;
      case 'A0C5': $value = 'a_Axe:Battle Hunger'; break;
      case 'A0C6': $value = 'a_Axe:Counter Helix'; break;
      case 'A0E2': $value = 'a_Axe:Culling Blade'; break;

      case 'A022': $value = 'a_Anti-Mage:Mana Break'; break;
      case 'AEbl': $value = 'a_Common:Blink'; break;
      case 'A0KY': $value = 'a_Anti-Mage:Spell Shield'; break;
      case 'A0E3': $value = 'a_Anti-Mage:Mana Void'; break;

      case 'A02H': $value = 'a_Balanar:Void'; break;
      case 'A08E': $value = 'a_Balanar:Crippling Fear'; break;
      case 'A086': $value = 'a_Balanar:Hunter in the Night'; break;
      case 'A03K': $value = 'a_Balanar:Darkness'; break;

      case 'A03D': $value = 'a_Banehallow:Summon Wolves'; break;
      case 'A02G': $value = 'a_Banehallow:Howl'; break;
      case 'A03E': $value = 'a_Banehallow:Feral Heart'; break;
      case 'A093': $value = 'a_Banehallow:Shapeshift'; break;

      case 'A0GJ': $value = 'a_Bara:Charge of Darkness'; break;
      case 'A0ML': $value = 'a_Bara:Charge of Darkness'; break;
      case 'A0ES': $value = 'a_Bara:Empowering Haste'; break;
      case 'A0G5': $value = 'a_Bara:Greater Bash'; break;
      case 'A0G4': $value = 'a_Bara:Nether Strike'; break;

      case 'A0EC': $value = 'a_Bloodseeker:Bloodrage'; break;
      case 'A0LE': $value = 'a_Bloodseeker:Blood Bath'; break;
      case 'A0LF': $value = 'a_Bloodseeker:Strygwyr\'s Thirst'; break;
      case 'A0LN': $value = 'a_Bloodseeker:Rupture'; break;

      case 'A004': $value = 'a_Bounty Hunter:Shuriken Toss'; break;
      case 'A000': $value = 'a_Bounty Hunter:Jinada'; break;
      case 'A07A': $value = 'a_Bounty Hunter:Wind Walk'; break;
      case 'A0B4': $value = 'a_Bounty Hunter:Track'; break;

      case 'A0BH': $value = 'a_Broodmother:Spawn Spiderlings'; break;
      case 'A0BG': $value = 'a_Broodmother:Spin Web'; break;
      case 'A0BK': $value = 'a_Broodmother:Incapacitating Bite'; break;
      case 'A0BP': $value = 'a_Broodmother:Insatiable Hunger'; break;

      case 'A00S': $value = 'a_Centaur:Hoof Stomp'; break;
      case 'A00L': $value = 'a_Centaur:Double Edge'; break;
      case 'A00V': $value = 'a_Centaur:Return'; break;
      case 'A01L': $value = 'a_Centaur:Great Fortitude'; break;

      case 'A055': $value = 'a_Chaos Knight:Chaos Bolt'; break;
      case 'A09F': $value = 'a_Chaos Knight:Blink Strike'; break;
      case 'A03N': $value = 'a_Chaos Knight:Critical Strike'; break;
      case 'A03O': $value = 'a_Chaos Knight:Phantasm'; break;

      case 'A0KM': $value = 'a_Chen:Penitence'; break;
      case 'A0LV': $value = 'a_Chen:Test of Faith'; break;
      case 'A069': $value = 'a_Chen:Holy Persuasion'; break;
      case 'A0LT': $value = 'a_Chen:Hand of God'; break;

      case 'A030': $value = 'a_Clinkz:Strafe'; break;
      case 'AHfa': $value = 'a_Clinkz:Searing Arrows'; break;
      case 'A025': $value = 'a_Clinkz:Wind Walk'; break;
      case 'A04Q': $value = 'a_Clinkz:Death Pact'; break;

      case 'A03F': $value = 'a_Dragon Knight:Breathe Fire'; break;
      case 'A0AR': $value = 'a_Dragon Knight:Dragon Tail'; break;
      case 'A0CL': $value = 'a_Dragon Knight:Dragon Blood'; break;
      case 'A03G': $value = 'a_Dragon Knight:Elder Dragon Form'; break;

      case 'A026': $value = 'a_Drow:Frost Arrows'; break;
      case 'ANsi': $value = 'a_Common:Silence'; break;
      case 'A0QB': $value = 'a_Common:Silence'; break;
      case 'A029': $value = 'a_Drow:Trueshot Aura'; break;
      case 'A056': $value = 'a_Drow:Marksmanship'; break;
      case 'A0VC': $value = 'a_Drow:Marksmanship'; break;

      case 'A04V': $value = 'a_Elemental:Enfeeble'; break;
      case 'A04X': $value = 'a_Elemental:Brain Sap'; break;
      case 'A04Y': $value = 'a_Elemental:Nightmare'; break;
      case 'A02Q': $value = 'a_Elemental:Fiend\'s Grip'; break;

      case 'A0DY': $value = 'a_Enchantress:Impetus'; break;
      case 'A0DX': $value = 'a_Enchantress:Enchant'; break;
      case 'A01B': $value = 'a_Enchantress:Nature\'s Attendants'; break;
      case 'A0DW': $value = 'a_Enchantress:Untouchable'; break;

      case 'A0B3': $value = 'a_Enigma:Malefice'; break;
      case 'A0B7': $value = 'a_Enigma:Conversion'; break;
      case 'A0B1': $value = 'a_Enigma:Midnight Pulse'; break;
      case 'AOBY': $value = 'a_Enigma:Black Hole'; break;

      case 'A06Q': $value = 'a_Furion:Sprout'; break;
      case 'A01O': $value = 'a_Furion:Teleportation'; break;
      case 'AEfn': $value = 'a_Furion:Force of Nature'; break;
      case 'A07X': $value = 'a_Furion:Wrath of Nature'; break;

      case 'A0JL': $value = 'a_Invoker:Quas'; break;
      case 'A0JM': $value = 'a_Invoker:Wex'; break;
      case 'A0JK': $value = 'a_Invoker:Exort'; break;
      case 'A0IY': $value = 'a_Invoker:Invoke'; break;

      case 'A05G': $value = 'a_Juggernaut:Blade Fury'; break;
      case 'A047': $value = 'a_Juggernaut:Healing Ward'; break;
      case 'A00K': $value = 'a_Juggernaut:Blade Dance'; break;
      case 'A066': $value = 'a_Juggernaut:Omnislash'; break;

      case 'A085': $value = 'a_Keeper:Blade Fury'; break;
      case 'A07Y': $value = 'a_Keeper:Healing Ward'; break;
      case 'A07N': $value = 'a_Keeper:Blade Dance'; break;
      case 'AEsv': $value = 'a_Keeper:Omnislash'; break;

      case 'A02M': $value = 'a_Krob:Carrion Swarm'; break;
      case 'ANsi': $value = 'a_Common:Silence'; break;
      case 'A02C': $value = 'a_Krob:Witchcraft'; break;
      case 'A03J': $value = 'a_Krob:Exorcism'; break;

      case 'AHtb': $value = 'a_Common:Storm Bolt'; break;
      case 'AUav': $value = 'a_Leoric:Vampiric Aura'; break;
      case 'AOcr': $value = 'a_Leoric:CriticaIStrike'; break;
      case 'A01Y': $value = 'a_Leoric:Reincarnation'; break;

      case 'A06W': $value = 'a_Lesh:Split Earth'; break;
      case 'A035': $value = 'a_Lesh:Diabolic Edict'; break;
      case 'A06V': $value = 'a_Lesh:Lightning Storm'; break;
      case 'A06X': $value = 'a_Lesh:Pulse Nova'; break;

      case 'A046': $value = 'a_Levi:Gush'; break;
      case 'A04E': $value = 'a_Levi:Kraken Shell'; break;
      case 'A044': $value = 'a_Levi:Anchor Smash'; break;
      case 'A03Z': $value = 'a_Levi:Ravage'; break;

      case 'A07F': $value = 'a_Lich:Frost Nova'; break;
      case 'A08R': $value = 'a_Lich:Frost Armor'; break;
      case 'A053': $value = 'a_Lich:Dark Ritual'; break;
      case 'A05T': $value = 'a_Lich:Chain Frost'; break;

      case 'A02J': $value = 'a_Lion:Impale'; break;
      case 'AOhx': $value = 'a_Common:Voodoo'; break;
      case 'A02N': $value = 'a_Lion:Mana Drain'; break;
      case 'A095': $value = 'a_Lion:Finger of Death'; break;

      case 'A05Y': $value = 'a_Lucy:Devour'; break;
      case 'A0FE': $value = 'a_Lucy:Scorched Earth'; break;
      case 'A094': $value = 'a_Lucy:LVL Death'; break;
      case 'ANdo': $value = 'a_Lucy:Doom'; break;

      case 'A02S': $value = 'a_Magnus:Shockwave'; break;
      case 'A037': $value = 'a_Magnus:Empower'; break;
      case 'A024': $value = 'a_Magnus:Mighty Swing'; break;
      case 'A06F': $value = 'a_Magnus:Reverse Polarity'; break;

      case 'A01D': $value = 'a_Maiden:Frost Nova'; break;
      case 'A04C': $value = 'a_Maiden:Frostbite'; break;
      case 'AHab': $value = 'a_Maiden:Brilliance Aura'; break;
      case 'A03R': $value = 'a_Maiden:Freezing Field'; break;

      case 'A012': $value = 'a_Medusa:Split Shot'; break;
      case 'A00Y': $value = 'a_Common:Chain Lightning:'; break;
      case 'ANms': $value = 'a_Medusa:Mana Shield'; break;
      case 'A02V': $value = 'a_Medusa:Purge'; break;

      case 'A0JQ': $value = 'a_Naix:Feast'; break;
      case 'A01E': $value = 'a_Naix:Poison Sting'; break;
      case 'A06Y': $value = 'a_Naix:Anabolic Frenzy'; break;
      case 'A028': $value = 'a_Naix:Rage'; break;

      case 'A05V': $value = 'a_Necro:Death Pulse'; break;
      case 'A01N': $value = 'a_Necro:Heartstopper Aura'; break;
      case 'A0MC': $value = 'a_Necro:Diffusion Aura'; break;
      case 'A060': $value = 'a_Necro:Sadist'; break;
      case 'A067': $value = 'a_Necro:Reaper\'s Scythe'; break;

      case 'A09K': $value = 'a_Nerub:Impale'; break;
      case 'A0X7': $value = 'a_Nerub:Impale'; break;
      case 'A02K': $value = 'a_Nerub:Mana Burn'; break;
      case 'A02L': $value = 'a_Nerub:Spiked Carapace'; break;
      case 'A09U': $value = 'a_Nerub:Vendetta'; break;

      case 'A0EY': $value = 'a_Nevermore:Shadowraze'; break;
      case 'A0BR': $value = 'a_Nevermore:Necromastery'; break;
      case 'A0FU': $value = 'a_Nevermore:Presence of the Dark Lord'; break;
      case 'A0HE': $value = 'a_Nevermore:Requiem of Souls'; break;
      case 'A0AJ': $value = 'a_Common:Special Attribute Bonus 2'; break;

      case 'AEsh': $value = 'a_Common:Shadow Strike'; break;
      case 'A0K9': $value = 'a_Common:Blink Strike'; break;
      case 'A03P': $value = 'a_PA:Blur'; break;
      case 'A03Q': $value = 'a_PA:Coup de Grace'; break;

      case 'A06I': $value = 'a_Pudge:Meat Hook'; break;
      case 'A06K': $value = 'a_Pudge:Rot'; break;
      case 'A06D': $value = 'a_Pudge:Flesh Heap'; break;
      case 'A0FL': $value = 'a_Pudge:Dismember'; break;

      case 'AHbz': $value = 'a_Pugna:Nether Blast'; break;
      case 'A0CE': $value = 'a_Pugna:Decrepify'; break;
      case 'A09D': $value = 'a_Pugna:Nether Ward'; break;
      case 'A0CC': $value = 'a_Pugna:Life Drain'; break;

      case 'AEsh': $value = 'a_Common:Shadow Strike'; break;
      case 'A0Q7': $value = 'a_Common:Shadow Strike'; break;
      case 'AEbl': $value = 'a_Common:Blink'; break;
      case 'A0ME': $value = 'a_Common:Blink'; break;
      case 'A04A': $value = 'a_Queen:Scream of Pain'; break;
      case 'A00O': $value = 'a_Queen:Sonic Wave'; break;

      case 'A05D': $value = 'a_Common:Frenzy'; break;
      case 'A0RY': $value = 'a_Common:Frenzy'; break;
      case 'A00Y': $value = 'a_Common:Chain Lightning'; break;
      case 'A00N': $value = 'a_Razor:Unholy Fervor'; break;
      case 'A04B': $value = 'a_Razor:Storm Seeker'; break;
      case 'A0YT': $value = 'a_Razor:Storm Seeker'; break;

      case 'A0FW': $value = 'a_Rigwarl:Viscous Nasal Goo'; break;
      case 'A0FX': $value = 'a_Rigwarl:Quill Spray'; break;
      case 'A0FY': $value = 'a_Rigwarl:Bristleback'; break;
      case 'A0FV': $value = 'a_Rigwarl:Warpath'; break;

      case 'A06O': $value = 'a_Sand King:Burrowstrike'; break;
      case 'A0H0': $value = 'a_Sand King:Sand Storm'; break;
      case 'A0FA': $value = 'a_Sand King:Caustic Finale'; break;
      case 'A06R': $value = 'a_Sand King:Epicenter'; break;

      case 'A05C': $value = 'a_Slardar:Sprint'; break;
      case 'A01W': $value = 'a_Slardar:War Stomp'; break;
      case 'A0JJ': $value = 'a_Slardar:Bash'; break;
      case 'A034': $value = 'a_Slardar:Amplify Damage'; break;

      case 'A04L': $value = 'a_Terrorblade:Soul Steal'; break;
      case 'A08Q': $value = 'a_Terrorblade:Conjure Image'; break;
      case 'AEvi': $value = 'a_Terrorblade:Metamorphosis'; break;
      case 'A07Q': $value = 'a_Terrorblade:Sunder'; break;

      case 'A08X': $value = 'a_Visage:Grave Chill'; break;
      case 'A0C4': $value = 'a_Visage:Soul Assumption'; break;
      case 'A0MD': $value = 'a_Visage:Gravekeeper\'s Cloak'; break;
      case 'A07K': $value = 'a_Visage:Raise Revenants'; break;

      case 'AEsh': $value = 'a_Common:Shadow Strike'; break;
      case 'Aven': $value = 'a_Veno:Poison Sting'; break;
      case 'A0MY': $value = 'a_Veno:Poison Sting'; break;
      case 'Arsw': $value = 'a_Veno:Plague Ward'; break;
      case 'A0MS': $value = 'a_Veno:Plague Ward'; break;
      case 'A013': $value = 'a_Veno:Poison Nova'; break;

      case 'A0LK': $value = 'a_Void:Time Walk'; break;
      case 'A0CZ': $value = 'a_Void:Backtrack'; break;
      case 'A081': $value = 'a_Void:Time Lock'; break;
      case 'A0J1': $value = 'a_Void:Chronosphere'; break;

      case 'A00T': $value = 'a_Weaver:Watcher'; break;
      case 'A0CA': $value = 'a_Weaver:Shukuchi'; break;
      case 'A0CG': $value = 'a_Weaver:Geminate Attack'; break;
      case 'A0CT': $value = 'a_Weaver:Time Lapse'; break;

      case 'A0DK': $value = 'a_Tauren:Fissure'; break;
      case 'A0SK': $value = 'a_Tauren:Fissure'; break;
      case 'A0DL': $value = 'a_Tauren:Enchant Totem'; break;
      case 'A0DJ': $value = 'a_Tauren:Aftershock'; break;
      case 'A0DH': $value = 'a_Tauren:Echo Slam'; break;

      case 'A01F': $value = 'a_Lina:Dragon Slave'; break;
      case 'A027': $value = 'a_Lina:Light Strike Array'; break;
      case 'A001': $value = 'a_Lina:Ultimate'; break;
      case 'A01P': $value = 'a_Lina:Laguna Blade'; break;
      case 'A09Z': $value = 'a_Lina:Laguna Blade'; break;

      case 'A042': $value = 'a_Luna:Lucent Beam'; break;
      case 'A041': $value = 'a_Luna:Moon Glaive'; break;
      case 'A062': $value = 'a_Luna:Lunar Blessing'; break;
      case 'A054': $value = 'a_Luna:Eclipse'; break;

      case 'A04W': $value = 'a_Ogre:Fireblast'; break;
      case 'A089': $value = 'a_Ogre:Fireblast'; break;
      case 'A011': $value = 'a_Ogre:Ignite'; break;
      case 'A007': $value = 'a_Ogre:Ignite'; break;
      case 'A083': $value = 'a_Ogre:Bloodlust'; break;
      case 'A088': $value = 'a_Ogre:Multi Cast'; break;

      case 'A06J': $value = 'a_Morph:Waveform'; break;
      case 'A0FM': $value = 'a_Morph:Morph Attack'; break;
      case 'A0KX': $value = 'a_Morph:Morph'; break;
      case 'A0F4': $value = 'a_Morph:Adapt'; break;
      case 'A04H': $value = 'a_Morph:Special Attribute Bonus 1'; break;

      case 'A063': $value = 'a_Naga:Mirror Image'; break;
      case 'A0BA': $value = 'a_Naga:Ensnare'; break;
      case 'A00E': $value = 'a_Naga:Comical Strike'; break;
      case 'A07U': $value = 'a_Naga:Song of the Siren'; break;

      case 'A08N': $value = 'a_Omni Knight:Purification'; break;
      case 'A08V': $value = 'a_Omni Knight:Repel'; break;
      case 'A06A': $value = 'a_Omni Knight:Degen Aura'; break;
      case 'A0ER': $value = 'a_Omni Knight:Guardian Angel'; break;

      case 'A06M': $value = 'a_Panda:Thunder Clap'; break;
      case 'Acdh': $value = 'a_Panda:Drunken Haze'; break;
      case 'Acdb': $value = 'a_Panda:Drunken Brawler'; break;
      case 'ANef': $value = 'a_Panda:Primal Split'; break;

      case 'A0DA': $value = 'a_Lancer:Spirit Lance'; break;
      case 'A0D7': $value = 'a_Lancer:Dopplewalk'; break;
      case 'A0DB': $value = 'a_Lancer:Juxtapose'; break;
      case 'A0D9': $value = 'a_Lancer:Phantom Edge'; break;

      case 'A010': $value = 'a_Rhasta:Forked Lightning'; break;
      case 'AOhx': $value = 'a_Common:Voodoo'; break;
      case 'A00P': $value = 'a_Rhasta:Hero Shackles'; break;
      case 'A00H': $value = 'a_Rhasta:Mass Serpent Ward'; break;

      case 'A0E6': $value = 'a_SA:Smoke Screen'; break;
      case 'A0RG': $value = 'a_SA:Smoke Screen'; break;
      case 'A0K9': $value = 'a_Common:Blink Strike'; break;
      case 'A0DZ': $value = 'a_SA:Backstab'; break;
      case 'A00J': $value = 'a_SA:Permanent Invisibility'; break;

      case 'A0KD': $value = 'a_Silencer:Curse of the Silent'; break;
      case 'A0LZ': $value = 'a_Silencer:Glaives of Wisdom'; break;
      case 'A0LR': $value = 'a_Silencer:Last Word'; break;
      case 'A0L3': $value = 'a_Silencer:Global Silence'; break;

      case 'A02A': $value = 'a_Silk:Magic Missile'; break;
      case 'A0AP': $value = 'a_Silk:Terror'; break;
      case 'A045': $value = 'a_Silk:Command Aura'; break;
      case 'ACac': $value = 'a_Silk:Command Aura'; break;
      case 'A00G': $value = 'a_Silk:Nether Swap'; break;
      case 'A0IN': $value = 'a_Silk:Nether Swap'; break;

      case 'A020': $value = 'a_Zues:Arc Lightning'; break;
      case 'A006': $value = 'a_Zues:Lightning Bolt'; break;
      case 'A0JC': $value = 'a_Zues:Lightning Bolt'; break;
      case 'A01X': $value = 'a_Zues:Static Field'; break;
      case 'A0N5': $value = 'a_Zues:Static Field'; break;
      case 'A07C': $value = 'a_Zues:Thundergod\'s Wrath'; break;

      case 'AHtb': $value = 'a_Common:Storm Bolt'; break;
      case 'A01K': $value = 'a_Sven:Great Cleave'; break;
      case 'A01M': $value = 'a_Sven:Toughness Aura'; break;
      case 'A01H': $value = 'a_Sven:God\'s Strength'; break;

      case 'A0BE': $value = 'a_Troll:Berserker Rage'; break;
      case 'A0BC': $value = 'a_Troll:Blind'; break;
      case 'A0BD': $value = 'a_Troll:Fervor'; break;
      case 'A0BB': $value = 'a_Troll:Rampage'; break;

      case 'A03Y': $value = 'a_Ursa:Earthshock'; break;
      case 'A059': $value = 'a_Ursa:Overpower'; break;
      case 'ANic': $value = 'a_Ursa:Fury Swipes'; break;
      case 'A0LC': $value = 'a_Ursa:Enrage'; break;

      case 'A05J': $value = 'a_Techies:Land Mines'; break;
      case 'A06H': $value = 'a_Techies:Stasis Trap'; break;
      case 'A06B': $value = 'a_Techies:Suicide Squad, Attack!'; break;
      case 'A0AK': $value = 'a_Techies:Remote Mines'; break;

      case 'A049': $value = 'a_Tinker:Laser'; break;
      case 'A05E': $value = 'a_Tinker:Heat Seeking Missile'; break;
      case 'A0BQ': $value = 'a_Tinker:March of the Machines'; break;
      case 'A065': $value = 'a_Tinker:Rearm'; break;

      case 'A0LL': $value = 'a_Tiny:Avalanche'; break;
      case 'A0BZ': $value = 'a_Tiny:Toss'; break;
      case 'A0BU': $value = 'a_Tiny:Craggy Exterior'; break;
      case 'A0CY': $value = 'a_Tiny:Grow'; break;

      case 'A01Z': $value = 'a_Trellen:Nature\'s Guise'; break;
      case 'A01V': $value = 'a_Trellen:Eyes in the Forest'; break;
      case 'A01U': $value = 'a_Trellen:Living Armor'; break;
      case 'A07Z': $value = 'a_Trellen:Overgrowth'; break;

      case 'A064': $value = 'a_Sniper:ScatterShot'; break;
      case 'A03S': $value = 'a_Sniper:Headshot'; break;
      case 'A03U': $value = 'a_Sniper:Take Aim'; break;
      case 'A04P': $value = 'a_Sniper:Assassinate'; break;

      case 'A05D': $value = 'a_Common:Frenzy'; break;
      case 'A09V': $value = 'a_Viper:Poison Attack'; break;
      case 'A0JP': $value = 'a_Viper:Corrosive Skin'; break;
      case 'A080': $value = 'a_Viper:Viper Strike'; break;

      case 'A0A5': $value = 'a_Syl:Summon Spirit Bear'; break;
      case 'A0AA': $value = 'a_Syl:Rabid'; break;
      case 'A0A8': $value = 'a_Syl:Synergy'; break;
      case 'A0AG': $value = 'a_Syl:True Form'; break;
      case 'A0AJ': $value = 'a_Common:Special Attribute Bonus 2'; break;

	//new skill
	case 'A0KV' : $value = 'a_Potm:Starfall'; break;
	case 'A0L8' : $value = 'a_Potm:Elune\'s Arrow'; break;
	case 'A0LN' : $value = 'a_Potm:Leap'; break;
	case 'A0KU' : $value = 'a_Potm:Moonlight Shadow'; break;

	case 'A0HW' : $value = 'a_Spectre:Spectral Dagger'; break;
	case 'A0FX' : $value = 'a_Spectre:Desolate'; break;
	case 'A0NA' : $value = 'a_Spectre:Dispersion'; break;
	case 'A0H9' : $value = 'a_Spectre:Haunt'; break;

	case 'A0NM' : $value = 'a_Witch Doctor:Paralyzing Cask'; break;
	case 'A0NE' : $value = 'a_Witch Doctor:Voodoo Restoration'; break;
	case 'A0NO' : $value = 'a_Witch Doctor:Maledict'; break;
	case 'A0NT' : $value = 'a_Witch Doctor:Death Ward'; break;
	case 'A0NX' : $value = 'a_Witch Doctor:Death Ward (Scepter)'; break;

	case 'A0OI' : $value = 'a_Obsidian:Arcane Orb'; break;
	case 'A0OJ' : $value = 'a_Obdisian:Astarl Imprisonment'; break;
	case 'A0OG' : $value = 'a_Obsidian:Essence Aura'; break;
	case 'A0OK' : $value = 'a_Obsidian:Sanitys Eclipse'; break;

	case 'A0J5' : $value = 'a_Warlock:Fatal Bonds'; break;
	case 'A0AS' : $value = 'a_Warlock:Shadow Word'; break;
	case 'A06P' : $value = 'a_Warlock:Upheaval'; break;
	case 'S008' : $value = 'a_Warlock:Rain Of Chaos'; break;

	case 'A0O7' : $value = 'a_Twin Head Dragon:Dual Breath'; break;
	case 'A0O6' : $value = 'a_Twin Head Dragon:Ice Path'; break;
	case 'A0O8' : $value = 'a_Twin Head Dragon:Auto Fire'; break;
	case 'A0O5' : $value = 'a_Twin Head Dragon:Macropyre'; break;

	case 'A0IL' : $value = 'a_Alchemist:Acid Spray'; break;
	case 'A0J6' : $value = 'a_Alchemist:Unstable Concoction'; break;
	case 'A0O3' : $value = 'a_Alchemist:Goblin\'s Greed'; break;
	case 'ANcr' : $value = 'a_Alchemist:Chemical Rage'; break;

	case 'A0O1' : $Value = 'a_Beast Master:Wild Axes'; break;
	case 'A0OO' : $value = 'a_Beast Master:Call Of The Wild'; break;
	case 'A0O0' : $value = 'a_Beast Master:Beast Rage'; break;
	case 'A0O2' : $value = 'a_Beast Master:Primal Roar'; break;

	case 'A0NB' : $value = 'a_Geomancer:Earth Bind';break;
	case 'A0N7' : $value = 'a_Geomancer:Geostrike';break;
	case 'A0MW' : $value = 'a_Geomancer:Divided We Stand';break;
	case 'A0N8' : $value = 'a_Geomancer:Poof';break;

	case 'A0NQ' : $value = 'a_Shadow Priest:Poison Touch';break;
	case 'A0OR' : $value = 'a_Shadow Priest:Shadow Wave';break;
	case 'A0OS' : $value = 'a_Shadow Priest:Shalow Grave';break;
	case 'A0NV' : $value = 'a_Shadow Priest:Weave';break;

        case 'A01I' : $value = 'a_Pit Lord:FireStorm';break;
        case 'A0RA' : $value = 'a_Pit Lord:Pit of Malice';break;
        case 'A0QT' : $value = 'a_Pit Lord:Expulsion';break;
	//unknown skill id
	default: $value = 'i_unknown skill id : '.$value;break;
    }
  }

  // items
  if ($value{1} != '_') {
    switch ($value) {
//Listed in order of where the shops are on sent (counter-clockwise)
//1
      case 'plcl': $value = 'i_Lesser Clarity Potion|50'; break;
      case 'sor8': $value = 'i_Flask of Sapphire Water|100'; break;
      case 'silk': $value = 'i_Ancient Tango of Essifation|80'; break;
      case 'sman': $value = 'i_Animal Courier|225'; break;
      case 'sor7': $value = 'i_Observer Wards|215'; break;
      case 'tgrh': $value = 'i_Sentry Wards|375'; break;
      case 'stwp': $value = 'i_Scroll of Town Portal|135'; break;
	case 'I00G': $value = 'i_Empty Bottle|700'; break;
//2
      case 'jdrn': $value = 'i_Mask of Death|900'; break;
      case 'tkno': $value = 'i_Ring of Regeneration|375'; break;
      case 'desc': $value = 'i_Kelen\'s Dagger of Escape|2150'; break;
      case 'shhn': $value = 'i_Sobi Mask|325'; break;
      case 'stre': $value = 'i_Gloves of Haste|610'; break;
      case 'tgxp': $value = 'i_Boots of Speed|500'; break;
      case 'oflg': $value = 'i_Gem of True Sight|750'; break;
      case 'gvsm': $value = 'i_Ultimate Orb|2300'; break;
//3
      case 'stwa': $value = 'i_Gauntlets of Ogre Strength|150'; break;
      case 'shdt': $value = 'i_Slippers of Agility|150'; break;
      case 'sbok': $value = 'i_Mantle of Intelligence|150'; break;
      case 'iwbr': $value = 'i_Ironwood Branch|57'; break;
      case 'bgst': $value = 'i_Belt of Giant Strength|450'; break;
      case 'belv': $value = 'i_Boots of Elvenskin|450'; break;
      case 'ciri': $value = 'i_Robe of the Magi|450'; break;
      case 'tbar': $value = 'i_Circlet of Nobility|185'; break;
      case 'sksh': $value = 'i_Ogre Axe|1000'; break;
      case 'tmmt': $value = 'i_Blade of Alacrity|1000'; break;
      case 'shtm': $value = 'i_Staff of Wizardry|1000'; break;
//4
      case 'tmsc': $value = 'i_Blades of Attack|650'; break;
      case 'sor2': $value = 'i_Broadsword|1200'; break;
      case 'whwd': $value = 'i_Quarterstaff|1150'; break;
      case 'sora': $value = 'i_Claymore|1400'; break;
      case 'flag': $value = 'i_Mithril Hammer|1610'; break;
      case 'modt': $value = 'i_Ring of Protection|175'; break;
      case 'tbsm': $value = 'i_Chainmail|620'; break;
      case 'ram3': $value = 'i_Plate Mail|1400'; break;
      case 'scul': $value = 'i_Helm of Iron Will|950'; break;
      case 'sor1': $value = 'i_Planeswalker\'s Cloak|650'; break;
	case 'rej4': $value = 'i_Stout shield|300'; break;
//5
      case 'rman': $value = 'i_Recipe Buriza-do Kyanon|1250'; break;
      case 'odef': $value = 'i_Recipe Monkey King Bar|1650'; break;
      case 'clsd': $value = 'i_Recipe Radiance|1525'; break;
      case 'rag1': $value = 'i_Recipe Heart of Tarrasque|1200'; break;
      case 'tsct': $value = 'i_Recipe Satanic|1100'; break;
      case 'lmbr': $value = 'i_Recipe Eye of Skadi|1250'; break;
      case 'fgfh': $value = 'i_Recipe The Butterfly|1400'; break;
      case 'rat9': $value = 'i_Recipe Refresher Orb|1875'; break;
      case 'evtl': $value = 'i_Recipe Guinsoo\'s Scythe of Vyse|450'; break;
//6
      case 'wlsd': $value = 'i_Recipe Sange and Yasha|900'; break;
      case 'totw': $value = 'i_Recipe Stygian Desolator|1200'; break;
      case 'rre2': $value = 'i_Recipe Crystalys|500'; break;
      case 'tdx2': $value = 'i_Recipe Black King Bar|1600'; break;
      case 'rde1': $value = 'i_Recipe Manta Style|1400'; break;
      case 'tst2': $value = 'i_Recipe Aegis of the Immortal|2000'; break;
      case 'rde3': $value = 'i_Recipe Lothar\'s Edge|1400'; break;
      case 'rhe3': $value = 'i_Recipe Dagon|1350'; break;
      case 'ajen': $value = 'i_Recipe Necronomicon|1300'; break;
      case 'hslv': $value = 'i_Recipe Linken\'s Sphere|1425'; break;
//7
      case 'I003': $value = 'i_Recipe Yasha|600'; break;
      case 'afac': $value = 'i_Recipe Sange|600'; break;
      case 'rhe1': $value = 'i_Recipe Cranium Basher|1460'; break;
      case 'rwat': $value = 'i_Recipe Blade Mail|500'; break;
      case 'moon': $value = 'i_Recipe Maelstrom|1300'; break;
      case 'manh': $value = 'i_Recipe Diffusal Blade|1550'; break;
      case 'pmna': $value = 'i_Recipe Mask of Madness|1050'; break;
      case 'shar': $value = 'i_Recipe Mekansm|900'; break;
	case 'gfor': $value = 'i_Recipe Eul\'s Scepter of Divinity|450'; break;
//8
      case 'ssil': $value = 'i_Recipe Headdress of Rejuvenation|225'; break;
      case 'rin1': $value = 'i_Recipe Netherezim Buckler|200'; break;
      case 'rspd': $value = 'i_Recipe Boots of Travel|2200'; break;
      case 'dsum': $value = 'i_Recipe Power Treads|420'; break;
      case 'pghe': $value = 'i_Recipe Hand of Midas|1400'; break;
      case 'rres': $value = 'i_Recipe Bracer|175'; break;
      case 'sres': $value = 'i_Recipe Wraith Band|175'; break;
      case 'kpin': $value = 'i_Recipe Null Talisman|175'; break;
//Secret Shop
      case 'srtl': $value = 'i_Demon Edge|2600'; break;
      case 'srbd': $value = 'i_Eaglehorn|3400'; break;
      case 'rump': $value = 'i_Messerschmidt\'s Reaver|3200'; break;
      case 'shrs': $value = 'i_Sacred Relic|3800'; break;
      case 'sprn': $value = 'i_Hyperstone|2300'; break;
      case 'rej6': $value = 'i_Ring of Health|875'; break;
      case 'shcw': $value = 'i_Void Stone|900'; break;
      case 'ram2': $value = 'i_Mystic Staff|2900'; break;
      case 'pgin': $value = 'i_Energy Booster|1000'; break;
      case 'hbth': $value = 'i_Point Booster|1200'; break;
      case 'oli2': $value = 'i_Vitality Booster|1100'; break;
//new item
	case 'I00R': $value = 'i_Recipe Arcane Ring|525'; break;
	case 'I00X': $value = 'i_Recipe Crow Curier|200'; break;
	case 'I013': $value = 'i_Recipe Vladimir\'s Offering|1000';break;
	case 'I01L': $value = 'i_Assault Cuirass|2000';break;
        case 'I01T': $value = 'i_Shiva\'s Guards|600';break;
        case 'I00Z': $value = 'i_Javelin|1400';break;
	case 'h015': $value = 'i_Circlet of Nobility|185'; break;
 	case 'h029': $value = 'i_Flask of Sapphire Water|100'; break;
	case 'h025': $value = 'i_Vitality Booster|1100'; break;
        case 'h01V': $value = 'i_Ring of Health|875'; break;
        case 'h023': $value = 'i_Stout shield|300'; break;
 	case 'h011': $value = 'i_Boots of Speed|500'; break;
	case 'h01F': $value = 'i_Gauntlets of Ogre Strength|150'; break;
	case 'h02N': $value = 'i_Recipe Bracer|175'; break;
	case 'h02E': $value = 'i_Scroll of Town Portal|135'; break;
	case 'h024': $value = 'i_Ultimate Orb|2300'; break;
	case 'h01E': $value = 'i_Energy Booster|1000'; break;
        case 'h01W': $value = 'i_Ring of Protection|175'; break;
	case 'h021': $value = 'i_Sobi Mask|325'; break;
	case 'h02A': $value = 'i_Ancient Tango of Essifation|80'; break;
  	case 'h026': $value = 'i_Void Stone|900'; break;
	case 'h016': $value = 'i_Belt of Giant Strength|450'; break;
 	case 'h012': $value = 'i_Gloves of Haste|610'; break;
	case 'h038': $value = 'i_Recipe Dagon|1350'; break;
	case 'h014': $value = 'i_Recipe Power Treads|420'; break;
	case 'h02R': $value = 'i_Recipe Sange|600'; break;
	case 'h017': $value = 'i_Blade of Alacrity|1000'; break;
	case 'h013': $value = 'i_Boots of Elvenskin|450'; break;
	case 'h02Q': $value = 'i_Recipe Yasha|600'; break;
	case 'h031': $value = 'i_Recipe Sange and Yasha|900'; break;
	case 'h01J': $value = 'i_Ironwood Branch|57'; break;
	case 'h028': $value = 'i_Lesser Clarity Potion|50'; break;
	case 'h02L': $value = 'i_Recipe Hand of Midas|1400'; break;
	case 'h01D': $value = 'i_Eaglehorn|3400'; break;
	case 'h01Q': $value = 'i_Ogre Axe|1000'; break;
	case 'h01O': $value = 'i_Mithril Hammer|1610'; break;
	case 'h02U': $value = 'i_Recipe Maelstrom|1300'; break;
	case 'h019': $value = 'i_Broadsword|1200'; break;
	case 'h035': $value = 'i_Recipe Black King Bar|1600'; break;
	case 'h01L': $value = 'i_Mantle of Intelligence|150'; break;
	case 'h02P': $value = 'i_Recipe Null Talisman|175'; break;
	case 'h01T': $value = 'i_Point Booster|1200'; break;
	case 'h01P': $value = 'i_Mystic Staff|2900'; break;
	case 'h01H': $value = 'i_Helm of Iron Will|950'; break;
	case 'h01M': $value = 'i_Mask of Death|900'; break;
	case 'h02O': $value = 'i_Recipe Wraith Band|175'; break;
	case 'h01B': $value = 'i_Claymore|1400'; break;
	case 'h037': $value = 'i_Recipe Lothar\'s Edge|1400'; break;
	case 'h01I': $value = 'i_Hyperstone|2300'; break;
	case 'h01A': $value = 'i_Chainmail|620'; break;
	case 'h01S': $value = 'i_Plate Mail|1400'; break;
	case 'h03S': $value = 'i_Assault Cuirass|2000';break;
	case 'h020': $value = 'i_Slippers of Agility|150'; break;
	case 'h01U': $value = 'i_Quarterstaff|1150'; break;
	case 'h01Y': $value = 'i_Robe of the Magi|450'; break;
	case 'h01X': $value = 'i_Ring of Regeneration|375'; break;
	case 'h02S': $value = 'i_Recipe Cranium Basher|1460'; break;
	case 'h03R': $value = 'i_Recipe Vladimir\'s Offering|1000';break;
	case 'h02C': $value = 'i_Observer Wards|215'; break;
	case 'h02K': $value = 'i_Recipe Boots of Travel|2200'; break;
	case 'h02F': $value = 'i_Animal Courier|225'; break;
	case 'h01G': $value = 'i_Gem of True Sight|750'; break;
	case 'h018': $value = 'i_Blades of Attack|650'; break;
	case 'h034': $value = 'i_Recipe Crystalys|500'; break;
	case 'h01C': $value = 'i_Demon Edge|2600'; break;
	case 'h03D': $value = 'i_Recipe Buriza-do Kyanon|1250'; break;
	case 'h02B': $value = 'i_Empty Bottle|700'; break;
	case 'h022': $value = 'i_Staff of Wizardry|1000'; break;
	case 'h03O': $value = 'i_Recipe Arcane Ring|525'; break;
	case 'h01K': $value = 'i_Kelen\'s Dagger of Escape|2150'; break;
	case 'h03W': $value = 'i_Shiva\'s Guards|600';break;
	case 'h01N': $value = 'i_Messerschmidt\'s Reaver|3200'; break;
	default: $value = 'i_unknown item id : '.$value;break;
    }
  }
  return $value;
}
?>
