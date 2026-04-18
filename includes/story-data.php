<?php
/**
 * Complete story tree with 15+ nodes and 3+ endings
 */
$storyTree = [
    'start' => [
        'text' => "You stand at the entrance of the Forgotten Crypt. Cold air seeps from within, carrying whispers of ancient secrets. The stone door creaks open, revealing darkness beyond.",
        'text_warrior' => "Your warrior instincts sense danger ahead. The weight of your weapon is reassuring as you face the darkness of the Forgotten Crypt.",
        'text_mage' => "Your magical senses tingle as you approach the Forgotten Crypt. Arcane energies pulse faintly from within.",
        'text_rogue' => "Your rogue's intuition notes every shadow and sound. The Forgotten Crypt holds secrets, and secrets are your specialty.",
        'choices' => [
            [
                'text' => 'Light a torch and proceed cautiously',
                'next' => 'crypt_entrance',
                'alignment' => 1,
                'ai_preview' => 'Proceeding with caution - wisdom favors the prepared.'
            ],
            [
                'text' => 'Rush in boldly, weapon ready',
                'next' => 'crypt_ambush',
                'alignment' => 1,
                'requires' => ['strength' => 12],
                'stat_cost' => ['health' => -10],
                'ai_preview' => '⚔️ Bold entry costs 10 HP but may yield greater rewards.'
            ],
            [
                'text' => 'Search for hidden passages outside',
                'next' => 'secret_entrance',
                'alignment' => 0,
                'requires' => ['agility' => 12],
                'ai_preview' => 'A rogue\'s approach - discover what others miss.'
            ]
        ]
    ],
    
    'crypt_entrance' => [
        'text' => "Torchlight flickers across ancient stone walls covered in faded murals. Two passages branch ahead - one descends deeper, the other leads to what appears to be a treasure chamber.",
        'text_warrior' => "Your torch reveals battle scenes carved into the walls. Warriors of old fought great evils here. The descent calls to your warrior spirit, but treasure gleams in the other direction.",
        'text_mage' => "Magical residue shimmers on the walls. These murals depict arcane rituals. The deeper passage hums with power, while the chamber radiates protective wards.",
        'choices' => [
            [
                'text' => 'Descend deeper into darkness',
                'next' => 'deep_crypt',
                'alignment' => 2,
                'stat_cost' => ['score' => 10],
                'ai_preview' => 'The harder path leads to greater glory. +10 score.'
            ],
            [
                'text' => 'Investigate the treasure chamber',
                'next' => 'treasure_room',
                'alignment' => -1,
                'ai_preview' => 'Wealth awaits, but greed has consequences.'
            ]
        ]
    ],
    
    'crypt_ambush' => [
        'text' => "You charge forward and immediately trigger a trap! Spikes shoot from the walls. You take damage but press on, finding yourself in a chamber with a spectral guardian.",
        'choices' => [
            [
                'text' => 'Fight the spectral guardian',
                'next' => 'guardian_battle',
                'alignment' => 1,
                'requires' => ['strength' => 14],
                'stat_cost' => ['health' => -20, 'score' => 25],
                'ai_preview' => '⚔️ Combat costs 20 HP. Victory grants +25 score.'
            ],
            [
                'text' => 'Attempt to reason with the spirit',
                'next' => 'spirit_dialogue',
                'alignment' => 2,
                'requires' => ['magic' => 12],
                'stat_cost' => ['score' => 15],
                'ai_preview' => 'Words over weapons. +15 score for wisdom.'
            ],
            [
                'text' => 'Flee back to entrance',
                'next' => 'crypt_entrance',
                'alignment' => -1,
                'ai_preview' => 'Discretion is sometimes the better part of valor.'
            ]
        ]
    ],
    
    'secret_entrance' => [
        'text' => "Your keen eyes spot a hidden mechanism behind loose stones. A secret passage opens, bypassing the main traps. You find yourself in an ancient library filled with forbidden knowledge.",
        'choices' => [
            [
                'text' => 'Study the forbidden texts',
                'next' => 'forbidden_knowledge',
                'alignment' => -1,
                'requires' => ['magic' => 10],
                'stat_cost' => ['magic' => 5, 'score' => 20],
                'ai_preview' => '📚 Dark knowledge increases magic by 5. +20 score.'
            ],
            [
                'text' => 'Take valuable scrolls and leave',
                'next' => 'treasure_room',
                'alignment' => -2,
                'stat_cost' => ['item_add' => 'ancient_scroll', 'score' => 15],
                'ai_preview' => 'Gain Ancient Scroll item. +15 score.'
            ],
            [
                'text' => 'Destroy the corrupt texts',
                'next' => 'purified_chamber',
                'alignment' => 3,
                'stat_cost' => ['score' => 30],
                'ai_preview' => 'A righteous act! +30 score.'
            ]
        ]
    ],
    
    'deep_crypt' => [
        'text' => "The air grows colder as you descend. Skeletal remains line the walls - warning of dangers ahead. A massive stone door blocks your path, inscribed with ancient runes.",
        'choices' => [
            [
                'text' => 'Decipher the magical runes',
                'next' => 'rune_puzzle',
                'alignment' => 1,
                'requires' => ['magic' => 14],
                'stat_cost' => ['score' => 20],
                'ai_preview' => 'Knowledge opens doors. +20 score.'
            ],
            [
                'text' => 'Force the door open',
                'next' => 'broken_door',
                'alignment' => 0,
                'requires' => ['strength' => 16],
                'stat_cost' => ['health' => -15, 'strength' => 2, 'score' => 15],
                'ai_preview' => '💪 Brute force costs 15 HP but increases strength by 2. +15 score.'
            ],
            [
                'text' => 'Search for another way',
                'next' => 'hidden_passage',
                'alignment' => 0,
                'requires' => ['agility' => 14],
                'ai_preview' => 'Patience reveals hidden paths.'
            ]
        ]
    ],
    
    'treasure_room' => [
        'text' => "Gold coins and jewels glitter everywhere. A magnificent sword rests on a pedestal in the center. Something feels wrong about this place.",
        'choices' => [
            [
                'text' => 'Take the sword',
                'next' => 'cursed_sword',
                'alignment' => -1,
                'stat_cost' => ['item_add' => 'cursed_sword', 'health' => -10, 'score' => 10],
                'ai_preview' => '🗡️ Gain Cursed Sword. Beware - it costs 10 HP.'
            ],
            [
                'text' => 'Take only what you need',
                'next' => 'modest_reward',
                'alignment' => 2,
                'stat_cost' => ['item_add' => 'healing_potion', 'score' => 15],
                'ai_preview' => 'Wisdom and restraint rewarded. +15 score.'
            ],
            [
                'text' => 'Leave everything untouched',
                'next' => 'trap_avoided',
                'alignment' => 1,
                'stat_cost' => ['score' => 5],
                'ai_preview' => 'Sometimes the safest choice is best.'
            ]
        ]
    ],
    
    'guardian_battle' => [
        'text' => "The spectral guardian attacks with ethereal fury! Your weapon passes through it, but your determination wounds its essence. Finally, it dissipates, leaving behind a glowing key.",
        'choices' => [
            [
                'text' => 'Take the key and proceed',
                'next' => 'inner_sanctum',
                'alignment' => 1,
                'stat_cost' => ['item_add' => 'spirit_key', 'score' => 20],
                'ai_preview' => '🔑 Spirit Key obtained. +20 score.'
            ],
            [
                'text' => 'Rest before continuing',
                'next' => 'inner_sanctum',
                'alignment' => 0,
                'stat_cost' => ['health' => 20, 'item_add' => 'spirit_key'],
                'ai_preview' => 'Regain 20 HP. Wisdom to rest before proceeding.'
            ]
        ]
    ],
    
    'spirit_dialogue' => [
        'text' => "The spirit pauses, surprised by your words. 'Few seek discourse before combat,' it says. 'I guard the tomb of Arkanis the Wise. What brings you here?'",
        'choices' => [
            [
                'text' => 'I seek knowledge to help my people',
                'next' => 'spirit_allied',
                'alignment' => 3,
                'stat_cost' => ['magic' => 3, 'score' => 30],
                'ai_preview' => '✨ Pure intentions rewarded. Magic +3, +30 score.'
            ],
            [
                'text' => 'I want the power within',
                'next' => 'spirit_test',
                'alignment' => -2,
                'ai_preview' => 'Power sought for selfish reasons faces judgment.'
            ],
            [
                'text' => 'I was merely exploring',
                'next' => 'spirit_mercy',
                'alignment' => 0,
                'ai_preview' => 'Honesty has its own rewards.'
            ]
        ]
    ],
    
    'forbidden_knowledge' => [
        'text' => "The texts reveal dark secrets of necromancy and soul-binding. The knowledge is intoxicating. You feel power flowing through you, but also a growing corruption.",
        'choices' => [
            [
                'text' => 'Embrace the dark knowledge',
                'next' => 'ending_dark_lord',
                'alignment' => -3,
                'is_ending' => true,
                'ending_type' => 'Tragic Failure',
                'stat_cost' => ['magic' => 10, 'score' => 40],
                'ai_preview' => '⚠️ The path of darkness leads to power... and corruption.'
            ],
            [
                'text' => 'Resist and close the books',
                'next' => 'purified_chamber',
                'alignment' => 2,
                'stat_cost' => ['score' => 25],
                'ai_preview' => 'Resisting temptation shows true strength.'
            ]
        ]
    ],
    
    'purified_chamber' => [
        'text' => "As you reject the dark knowledge, the chamber transforms. A warm light fills the room, revealing a hidden altar with a blessed amulet.",
        'choices' => [
            [
                'text' => 'Take the blessed amulet',
                'next' => 'inner_sanctum',
                'alignment' => 2,
                'stat_cost' => ['item_add' => 'blessed_amulet', 'health' => 30, 'score' => 25],
                'ai_preview' => '✨ Blessed Amulet obtained. Health +30, +25 score.'
            ],
            [
                'text' => 'Pray at the altar',
                'next' => 'ending_heroic',
                'alignment' => 3,
                'is_ending' => true,
                'ending_type' => 'Heroic Victory',
                'stat_cost' => ['score' => 50],
                'ai_preview' => '🏆 A true hero\'s path leads to victory!'
            ]
        ]
    ],
    
    'rune_puzzle' => [
        'text' => "Your magical knowledge allows you to decipher the ancient runes. They speak of a great evil sealed below and the key to its eternal prison.",
        'choices' => [
            [
                'text' => 'Strengthen the seal',
                'next' => 'ending_heroic',
                'alignment' => 3,
                'is_ending' => true,
                'ending_type' => 'Heroic Victory',
                'stat_cost' => ['score' => 60],
                'ai_preview' => '🌟 You have chosen to protect the world! Heroic Victory!'
            ],
            [
                'text' => 'Weaken the seal slightly to learn more',
                'next' => 'ending_secret',
                'alignment' => 0,
                'is_ending' => true,
                'ending_type' => 'Secret Path',
                'requires' => ['magic' => 18],
                'stat_cost' => ['score' => 45],
                'ai_preview' => '🔮 Curiosity opens a unique path... Secret Ending approaches.'
            ]
        ]
    ],
    
    'broken_door' => [
        'text' => "Your strength shatters the ancient door, but the noise awakens something terrible. A lich emerges from the darkness, ancient and powerful!",
        'choices' => [
            [
                'text' => 'Fight the lich',
                'next' => 'ending_tragic',
                'alignment' => 1,
                'is_ending' => true,
                'ending_type' => 'Tragic Failure',
                'requires' => ['strength' => 20],
                'stat_cost' => ['health' => -50, 'score' => 20],
                'ai_preview' => '⚔️ A valiant but doomed last stand...'
            ],
            [
                'text' => 'Attempt to flee',
                'next' => 'ending_tragic',
                'alignment' => -1,
                'is_ending' => true,
                'ending_type' => 'Tragic Failure',
                'ai_preview' => '🏃 Some evils cannot be outrun...'
            ]
        ]
    ],
    
    'hidden_passage' => [
        'text' => "Your patience reveals a narrow crack in the wall. Squeezing through, you discover the lich's true treasure chamber - untouched for centuries.",
        'choices' => [
            [
                'text' => 'Take the legendary artifact',
                'next' => 'ending_secret',
                'alignment' => 0,
                'is_ending' => true,
                'ending_type' => 'Secret Path',
                'stat_cost' => ['item_add' => 'legendary_artifact', 'score' => 55],
                'ai_preview' => '💎 You found what others missed! Secret Ending!'
            ]
        ]
    ],
    
    'cursed_sword' => [
        'text' => "As you grasp the sword, dark energy courses through you. The blade whispers promises of power, but demands blood.",
        'choices' => [
            [
                'text' => 'Accept the curse',
                'next' => 'ending_dark_lord',
                'alignment' => -3,
                'is_ending' => true,
                'ending_type' => 'Tragic Failure',
                'stat_cost' => ['strength' => 10, 'score' => 30],
                'ai_preview' => '😈 The cursed blade claims another soul...'
            ],
            [
                'text' => 'Try to break the curse',
                'next' => 'spirit_dialogue',
                'alignment' => 2,
                'requires' => ['magic' => 15],
                'stat_cost' => ['health' => -20, 'score' => 15],
                'ai_preview' => 'Seeking redemption costs 20 HP but offers hope.'
            ]
        ]
    ],
    
    'modest_reward' => [
        'text' => "You take only a healing potion and some gold coins. As you turn to leave, a hidden door opens - your restraint has been rewarded.",
        'choices' => [
            [
                'text' => 'Enter the hidden door',
                'next' => 'inner_sanctum',
                'alignment' => 2,
                'stat_cost' => ['score' => 25],
                'ai_preview' => 'Virtue opens hidden paths. +25 score.'
            ]
        ]
    ],
    
    'trap_avoided' => [
        'text' => "Your wisdom saves you. As you leave, you spot the pressure plates that would have triggered deadly traps. You find a safer route forward.",
        'choices' => [
            [
                'text' => 'Continue carefully',
                'next' => 'inner_sanctum',
                'alignment' => 1,
                'stat_cost' => ['score' => 10],
                'ai_preview' => 'Caution leads safely onward.'
            ]
        ]
    ],
    
    'spirit_allied' => [
        'text' => "The spirit smiles. 'Your heart is pure. I shall aid you against the darkness below.' It grants you its blessing and opens the way.",
        'choices' => [
            [
                'text' => 'Accept the blessing and proceed',
                'next' => 'ending_heroic',
                'alignment' => 3,
                'is_ending' => true,
                'ending_type' => 'Heroic Victory',
                'stat_cost' => ['health' => 50, 'magic' => 5, 'score' => 70],
                'ai_preview' => '👑 Blessed by the ancients! Heroic Victory awaits!'
            ]
        ]
    ],
    
    'spirit_test' => [
        'text' => "The spirit's eyes narrow. 'Power for its own sake corrupts. You must prove yourself worthy.' It attacks!",
        'choices' => [
            [
                'text' => 'Fight back with everything',
                'next' => 'guardian_battle',
                'alignment' => -1,
                'stat_cost' => ['health' => -30],
                'ai_preview' => '⚔️ Your greed has consequences...'
            ]
        ]
    ],
    
    'spirit_mercy' => [
        'text' => "The spirit considers your honesty. 'Few admit to aimless wandering. Perhaps fate guided you here. I shall give you a chance to prove yourself.'",
        'choices' => [
            [
                'text' => 'Accept the challenge',
                'next' => 'rune_puzzle',
                'alignment' => 1,
                'stat_cost' => ['score' => 15],
                'ai_preview' => 'Fate offers a second chance.'
            ]
        ]
    ],
    
    'inner_sanctum' => [
        'text' => "You enter the heart of the crypt. An ancient altar pulses with dark energy. The source of the corruption is clear - a sealed sarcophagus bound with silver chains.",
        'choices' => [
            [
                'text' => 'Perform the sealing ritual',
                'next' => 'ending_heroic',
                'alignment' => 3,
                'is_ending' => true,
                'ending_type' => 'Heroic Victory',
                'requires' => ['magic' => 15],
                'stat_cost' => ['score' => 65],
                'ai_preview' => '🌟 The ritual will seal the evil forever! Heroic Victory!'
            ],
            [
                'text' => 'Break the chains',
                'next' => 'ending_dark_lord',
                'alignment' => -3,
                'is_ending' => true,
                'ending_type' => 'Tragic Failure',
                'requires' => ['strength' => 18],
                'stat_cost' => ['score' => 25],
                'ai_preview' => '⚠️ Breaking the chains releases ancient evil!'
            ],
            [
                'text' => 'Use the Spirit Key',
                'next' => 'ending_secret',
                'alignment' => 1,
                'is_ending' => true,
                'ending_type' => 'Secret Path',
                'requires' => ['item' => 'spirit_key'],
                'stat_cost' => ['score' => 50],
                'ai_preview' => '🔑 The Spirit Key reveals hidden truths...'
            ]
        ]
    ],
    
    // Endings
    'ending_heroic' => [
        'text' => "You have triumphed over darkness! The evil is sealed, the crypt is purified, and the surrounding lands are safe once more. Bards will sing of your deeds for generations!",
        'is_ending' => true,
        'ending_type' => 'Heroic Victory',
        'choices' => []
    ],
    
    'ending_tragic' => [
        'text' => "The darkness overwhelms you. Your adventure ends here, another fallen hero in the depths of the Forgotten Crypt. Perhaps another will succeed where you failed...",
        'is_ending' => true,
        'ending_type' => 'Tragic Failure',
        'choices' => []
    ],
    
    'ending_secret' => [
        'text' => "You have discovered the crypt's greatest secret - it was never a prison for evil, but a test for the worthy. The ancient guardians acknowledge your unique path. You leave forever changed, carrying wisdom few have ever gained.",
        'is_ending' => true,
        'ending_type' => 'Secret Path',
        'choices' => []
    ],
    
    'ending_dark_lord' => [
        'text' => "The dark power corrupts you completely. You emerge from the crypt not as a hero, but as a new dark lord. The cycle begins anew, and one day, another adventurer will come to challenge you...",
        'is_ending' => true,
        'ending_type' => 'Tragic Failure',
        'choices' => []
    ]
];
?>