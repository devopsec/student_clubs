# Contributing to student_clubs

These are some of the best best practices and guidelines that should be followed when contributing to the project.

## Create an Issue

- First, create an issue to let everyone know you would like to work on a section of code, bug, or add some new feature.
This allows a discussion to be opened and feedback / advise to given if necessary.
- Make sure to mention the <b>main</b> topic, bug, or feature in the title of the issue.

## Fork the Repo

Fork a clone of the repo to your github and clone the fork to your machine.<br>
This can be done using your preferred IDE (tutorial for Jetbrains Clion to come..) or using the git cmd:

```
git clone <your fork url>
```


## Merging changes

- Make changes, and commit to your repo, putting <b>comments</b> inline as needed and giving a general description in commit message.
- Make a pull request on github or through git, your changes will be reviewed.
- If there are conflicts that need resolved, we will start a discussion and provide any help necessary.
- Then your changes will be merged in with the master repo and your name will be added to the contributions documentation (Thank You!)

## General guidelines

1. Make sure you configure your git hooks appropriately before making any commits to the project. You can find the 
git hooks we use in the [resources/git/](git resources) directory, along with some other useful scripts. For instructions 
on adding them to your repo read the `Usage` section in the comments.

2. Make your code self-documenting, ```ts``` is not a good name for a timestamp variable. Rather, you should use identifiers that
another developer can take a glance at and understand what it does, ```epoch_timestamp``` on the other-hand is un-mistakable,
and your intention is crystal clear.

3. Provide inline documentation comments for all functions, wrappers, abstractions, and larger routines. Also, if a declaration
is not perfectly clear or calls external functions / routines add in a quick comment describing where and what the routine is calling.
  
## Test and Debugging

More to follow...

## Documentation

- Provide instructions, and code examples on how to replicate what you are describing, or how to perform the operations.
Nobody likes having to email their coworkers to figure out what the code they just pulled down does, so please lend the 
same courtesies to your fellow dev's as you would if you were stuck next to them in cubical city all day.
- When creating issues for bugs, provide screen-shots, code snippets, and instructions on replicating the issue. 

