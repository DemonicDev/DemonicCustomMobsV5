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




## old readme:
Updated partly to pmmp5 with completely new Base, Pathfinding not included for now
Help Wanted with bug hunting, if u manage to crash the plugin, pls Open an issue with the crash Dump :D
# UPDATE 0.0.6 -predev
CustomMobs via Customies working
HumanMobs working but can crash since not fully done
Extremly unstable, but u can spawn already Mobs
-> No AI

# DemonicCustomMobsV5
# ⚠️the tutorial isnt up to date! just take customies_mobs.yml as example and important u need the customies plugin for this to work
A Pocketmine Plugin, which Provides the posibility to add Custom Mobs, with placing the data into the Plugin_data folder
![image](https://user-images.githubusercontent.com/61244099/234644632-7d42b63b-68be-4f54-8bc2-960fe0adaebb.png)

Setup:
1.add the Mobname {name} into the Moblist.yml 
```yaml
---
mobs: 
 - "{name}"
 - "boar"
# - "3rd Mob" 
# - "4th Mob" 
# - "5th Mob"
#... and going so on


...
```
2. make a folder called "{name}"
3. add in it {name}.png {name}.geo.json and {name}.yaml
4. add in {name}.yaml as example
```yaml
---
# THE NAME HAVE TO BE THE SAME AS THE NAME IN MOBLIST
# THE FOLDER NAME, WHERE THIS YML FILE IS IN, HAS TO BE THE SAME HOW THE NAME
# pls do {name}.yml ; {name}.png and {name}.geo
# in {name}.geo pls use geometry.{name}
damage: 0
health: 20
speed: 0.7
scale: 1.4
#working on size of hitbox
#size: (new EntitySizeInfo(1.8, 1.8));
#setNameTagAlwaysVisible:
#Ai: "passive"
Ai: "hostile"
drops:
# - [Id, Meta, Amount_min, Amount_Max Percentage/probability 1-100]
  - [277, 0, 1, 1, 100]
  - [276, 0, 1, 1, 80]
  - [266, 0, 10, 16, 100]
...
```
you can use until now for passive or hostile ai, i will add later some more and will bugfix them and will add later an api, so you can register your own Ais.

pls note, that DCMv5 is using HumanEntities... and cuz of this you can only use skins with the sizes 128x128, 64x64 and 64x32 pixels
5. Install https://github.com/Matze997/Pathfinder for now, cuz i have to add it later as virion , but i didnt had the time until now to add it as a virion

Note: it is the first stable version, and it needs a spawn map, i wrote once a spawn map, and i will show u a example, i wrote in short time for an older version of this plugin
