# MultiWarp
A Warp With Multiple Locations!

# Feature
- One Warp, Multiple possible locations! Even if it's across worlds!
- WarpGroup System, Allowing you to have multiple WarpGroup in each world.
- Weights, Allowing you to set the odds of a WarpPoint being selected.
- Supports minecraft command autofill, allowing ease of navigating commands.
- Informative help command that shows subcommand description, and aliases

# Commands
- Main command `/multiwarp`
<br>Check `/multiwarp help` for more in depth info
- System command `/multiwarpuse <group> "player"`
<br>This silent command is meant to be executed by console/plugin integrations

# Setup
1. Create a WarpGroup - You will need one before you can add WarpPoints in it.
<br>`/mulitwarp create %groupname%`
2. Add a few WarpPoints into the WarpGroup - It will be added on your current location.
<br>`/mulitwarp add %groupname% %weight% %facing%`
    - Weight: Chance of a warp being selected is {WarpPoint Weight}:{Sum of All WarpPoints Weight}.
    <br>Increase it to increase the odds.
    - Facing: if facing your facing direction should be stored, and applied, when they use the warp.
    
3. Repeat the process until you have a satisfied amount of warps.
4. Grab your slapper or taptodo plugin, in fact any plugin that can execute commands, and supports player name will work
5. Go to the block you want to set up as the entry point, add the command `/multiwarpuse %groupname% "%player%"`
<br>It's important to remember to put the put quotes around the player parameter, because some players may have space in their names 
