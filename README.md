# DemonicCustomMobsV5
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
