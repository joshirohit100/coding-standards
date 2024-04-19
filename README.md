# Some custom PHP sniffs for Drupal / PHP Projects 
Provide some custom PHP Sniffs for the Drupal / PHP based projects.

## How to add this to project
In project root composer.json file, add below entry under `repositories`
section -

````
"type": "git",
"url": "git@github.com:joshirohit100/rj-coding-standards.git"
````
Then run the composer require command
```
composer require joshirohit100/rj-coding-standards
```

## Sniffs
### DrupalLibraryVersionPattern
Provides a sniff to check the `version` key in the drupal *.libraries.yml files.
Sample library definition
```
test.my_library:
    version: 1.x
    js:
        somejsfilepath/jsfile.js
```
This will fail the sniff because library version is not proper
like 1.0.0 (check for only number and dot in library version)

To add this sniff in project, just add below in phpcs.xml file
```
<rule ref="DrupalLibraryVersionPattern"/>
```

### DrupalUpdateHookSequence
Provides a sniff to check the order of update hooks in *.install file.

Update hooks should be in decreasing sequence.
This means, latest version should be on top.

To add this sniff in project, just add below in phpcs.xml file
```
<rule ref="DrupalUpdateHookSequence"/>
```

### DrupalHookUpdateNComment
Provides a sniff to not allow "hook_update_N()" in update hooks comment in *.install file.

To add this sniff in project, just add below in phpcs.xml file
```
<rule ref="DrupalHookUpdateNComment"/>
```

### DrupalHookInstallLast
Provides a sniff to check for hook_install() should always be at bottom in file, after all update hooks.

To add this sniff in project, just add below in phpcs.xml file
```
<rule ref="DrupalHookInstallLast"/>
```
