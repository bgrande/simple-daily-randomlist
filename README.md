# 2.0.0

# About
Sorts an array or defined json list for example of names by random either
* by hosted json list or
* by uploaded json list

# Requirements
* a file `src/list.json` with json array content (["foo", "bar"] -> see `src/example.json`) must be created for a hosted list

# Todo
* Export file representation to model (which will be injected into sorting class)
* Separate File handling and sorting into different classes (only one sort class)
* restrict rights for files in src/
* improve administration settings and checking
* make list durability configurable (not by day per default)
* use classes for fileid and session management, maybe for output management, too... Do not forget tests
* upload through cli?
* captcha