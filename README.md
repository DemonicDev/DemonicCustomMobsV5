# 🎉 DCMv5 made it to PMMP5! 🎉
## Installation:
1. Download the DCMv5.phar
2. put it in Plugins
3. if u want to use the CustomiesNode, u need to download [Customies](https://github.com/CustomiesDevs/Customies)
4. For Customies Node add to customies_mobs.yml
```yml
YourMobName:
  damage: 0 # how much damage should ur mob do (more important for future hostile ai)
  health: 20 # how much health should your mob have?
  speed: 0.7 # how fast should your mob be? (to get a feeling you should play with this)
  scale: 1.7 # scale is the factor of size of your mob
  size: [1.0, 0.7] # hit box of your mob [height, width]
  ai: "passive" # which ai to use, currently we got only passive (working on hostile, and the possibility to add AIs by plugins)
  id: "scc:slate_boar" # network id of the mob, it should match the id of the mob in your resource pack
  drops: # Drops on death
  #  - [stringid of item, min amount, max amount, drop chance from 1 to 100]
    - ["iron_ingot", 1, 2, 40]
```
5. put your Resourcepack.zip into the resource_packs folder and add it to resource_packs/resource_packs.yml
6. If you Want to use HumanNode you need to add the entity data in human_mobs.yml
```yml
YourMobName:
  damage: 0 
  health: 20
  speed: 0.7
  scale: 1.7
  size: [1.0, 0.7]
  ai: "passive" # as you might see till here like with customiesMobs
  skin: "boar" # instead of an id we use skins for this type of mobs
  drops:
  #  - [stringid of item, min amount, max amount, drop chance from 1 to 100]
    - ["iron_ingot", 1, 2, 40]
```
7. make a new folder in the plugin_data folder of this plugin, with a "name"
  -> inside u put a "name".png (a "name" picture) with [64x32, 64x64 or 128x128] pixels
  -> also put a "name".geo.json in this folder, you can generate those with BlockBench!
     ⚠️ make sure inside the geo.json under description, the value for identifier is geometry."name"
8. Start the Server :D
9. Spawn your mob with
    -> for CustomiesNode: /cm-spawn c name 
    -> for HumanNode: /cm-spawn h name
### Hint:
 - instead of c u can put custom and instead of h u can put human
 - u can also enable both nodes at the same time!
## Disclaimer!
Human Mobs dont have animation, me personaly, i do not know if pocketmine supports that at the moment for custom geometries!
If you have a suggestion or idea how to fix that, feel free to open an issue

## More
### Thanks to 
Matze997 for making his Pathfinder Virion which i use for my pathfinding api which can be called by Ai Classes.
Customies Team, for makeing Customies and thanks to the people that Maintain customies
### How you can help make this Awesome plugin even better
If you manage to crash this Plugin or find a bug, feel free to open an Issue! 
I would be happy, since it would help make me the plugin more bullet proof!

# Todo:
  Find Ways to crash this plugin, to fix those Bugs [ ] ⚠️HELP WANTED
  AI: (Mob Behaviour)
  1. Improve Passive AI []
  2. Add Hostile AI []
  3. add Golem Like AI []
  4. add maybe some custom AI []
  5. add API for Developers to add AIs []
  Reconstruct Spawning Code and add API for Spawning
  Add a SpawnMap []
