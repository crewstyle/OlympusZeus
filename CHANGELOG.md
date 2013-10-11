## 1.4.9.4 (October 7, 2013)

Make the new version completely compatible with old versions, and add new display and new descriptions on configuration pages.


## 1.4.9.3 (September 25, 2013)

Fix bug on static functions.


## 1.4.9.2 (September 25, 2013)

Fix bug on flush rewrite rules.


## 1.4.9.1 (September 25, 2013)

Fix bug on static function and move flush rewrite rules on updating CPT.


## 1.4.9 (September 25, 2013)

iOS 7 compliant! Euh... bad subject :p
New default configuration pages and enhancement about Custom post types: you can now add custom fields on post types.


## 1.4.8 (September 18, 2013)

Fix lots of bugs about networks


## 1.4.7.5 (September 18, 2013)

Fix CRON access bug


## 1.4.7.4 (September 16, 2013)

Add description to pages


## 1.4.7.3 (September 16, 2013)

Fix small bug on dashboard edit page


## 1.4.7.2 (September 16, 2013)

Fix small bug on font saved contents


## 1.4.7.1 (September 15, 2013)

Fix small bug


## 1.4.7 (September 15, 2013)

Make this new version works with the old Tea T.O.
Add separator to submenu.


## 1.4.6 (August 25, 2013)

Fix lots of bugs on network field.
Add new French package.


## 1.4.5 (August 23, 2013)

Better integration in fields


## 1.4.4 (August 22, 2013)

Add new uninstall.php to make uninstall works
Fix uninstall bug


## 1.4.3 (August 21, 2013)

Better compatibility with Wordpress 3.4.2 and uses of register_uninstall_hook instead of register_desactivation_hook


## 1.4.2 (August 21, 2013)

Main class contains now only what she is supposed to do: a container for everything


## 1.4.1 (August 21, 2013)

Fix retrocompatibility with Wordpress 3.4 version

- **Fix:**
  - Bug on footer layout


## 1.4.0 (August 20, 2013)

Get real advices from Nicolas A. <https://twitter.com/nicolassing> from Take a Tea :)

- **Edit:**
  - all components are now in different folders
  - each field in a single package
  - <checkbox>, <multiselect>, <radio> and <select> or now uniq (<choice> component is dead)
- **New:**
  - Abstract classes are now introduced for fields


## 1.3.2.1 (August 14, 2013)

- **Fix:**
  - fix bug on small icon


## 1.3.2 (August 14, 2013)

NEW CUSTOM POST TYPE
You can now manage all your CPT directly from the Tea TO dashboard


## 1.3.1 (August 13, 2013)

Responsified!


## 1.3.0 (August 10, 2013)

NEW PLUGIN VERSION
- with new images
- with a Wordpress plugin readme version


## 1.2.12 (July 31, 2013)

Fix small bug in Twitter template
Display update date on network templates
Adds descriptions on documentation page


## 1.2.12 (July 31, 2013)

Make all business code in one single function, add <twitter> connection and make new Wordpress CRON schedules to update DB

- **New:**
  - new <twitter> field with API, connection and more
  - new Wordpress CRON schedules to update networks contents in DB and cache
- **Edit:**
  - business code for networks is now in one single function: updateNetwork


## 1.2.11 (July 30, 2013)

Fix some bugs with Instagram recent medias and FlickR username

- **Fix:**
  - fix Instagram recent medias bug
  - fix FlickR username bug


## 1.2.10 (July 30, 2013)

New <flickr> field with API

- **New:**
  - new <flickr> field with API, connection and more


## 1.2.9 (July 29, 2013)

Edit header layout without form, new <instagram> field with API, new _del_option function

- **Edit:**
  - add submit option in header layout: no form without button ;)
- **New:**
  - new <instagram> field with API, connection and more
  - new _del_option function to delete option from DB and transient


## 1.2.8 (July 26, 2013)

Detele Date field and add new RTE field.

- **Edit:**
  - delete <date> field 'cause it useless too...
  - optimize JS scripts
- **New:**
  - new <rte> field to get all Wordpress powaaa :)


## 1.2.7 (July 26, 2013)

Update Background field and fix lots of bugs

- **Edit:**
  - update <background> field to a better experience
  - delete <image> field 'cause it was... hum... useless
- **Fix:**
  - Enqueue new media script to be compliant with Wordpress 3.5.2


## 1.2.6 (July 23, 2013)

Update Google font field and optimize script

- **Edit:**
  - delete all switches to let if/else instead
  - update Google <font> field to display to 18 fonts
- **Fix:**
  - Fix small bug on README.md


## 1.2.5 (July 11, 2013)

New usefull field and fix bug

- **Fix:**
  - fix <multiselect> forgotten field
- **New:**
  - new <include> field to display everything you want


## 1.2.4 (July 11, 2013)

README.md up to date with default documentation page

- **Edit:**
  - README.md


## 1.2.3 (May 27, 2013)

Get real advices from Xavier C. <https://twitter.com/xavismeh> :)

- **Edit:**
  - assets are now enabled in all Wordpress admin pages (a big news is coming ;))
  - back to <checkbox>/<select>/<radio> instead of <choice> (not userfriendly)
  - icons are now defined in the TeaTO and not settable anymore
  - no more "__categories" special name anymore: you can set "__category" for simple or multiple choices
  - public keys are now privates
- **Fix:**
  - fix <hidden> field which does not need description or title attributes
- **New:**
  - here comes the new default TeaTO Documentation page (appears even if you have no settings)
  - new <features> and <list> fields to display contents
  - new way to disable Wordpress scripts/styles on the TeaTO custom pages


## 1.2.2 (May 27, 2013)

Fix some bugs, adds new fields, adds new default documentation page, better media-views integration, new scripts, and more...
Details on the next commit


## 1.2.1 (May 14, 2013)

Fix some small bugs

- **Fix:**
  - delete a forgotten enclosure
- **New:**
  - add TeaTO version


## 1.2.0 (May 14, 2013)

Add some new fields and fix small bugs

- **Edit:**
  - edit all TeaTO definition by setting only pages (no more subpages now)
  - edit <category>/<menu>/<page>/<post>/<posttype>/<tag> fields with some extra options in a WordpressContents function
- **Fix:**
  - fix the empty color value
- **New:**
  - add Background field with all needed options
  - add new page config to hide submit button
  - prepare default documentation page with no options


## 1.1.1 (May 03, 2013)

Add some new fields and fix small bugs

- **Edit:**
  - edit <social> field to include label and link data
- **New:**
  - add Wordpress admin bar links
  - add some Defaults values
  - add _set_option() function to the Tea TO package
  - add Paragraph field
  - add rows option to <textarea> field
  - prepare RTE and Date new fields


## 1.1.0 (April 25, 2013)

Add some new fields and fix small bugs

- **Edit:**
  - edit <br/> and <hr/> fields
  - edit <text> field with some extra options instead of number/range/email/password/search/url fields
- **Fix:**
  - fix <font> field
- **New:**
  - add _get_option() function to the Tea TO package
  - add Choice field with some extra options instead of checkbox/radio/select/multiselect fields


## 1.0.3 (March 31, 2013)

Some improvments on checkbox fields and new social icons

- **New:**
  - add an "Un/select all checkboxes" on image and social fields
  - add Bloglovin, Hellocoton and Youtube social icons


## 1.0.2 (March 31, 2013)

Add a small checkbox feature

- **New:**
  - add an "Un/select all checkboxes" on checkbox field


## 1.0.1 (March 26, 2013)

List now all next todos and add some extra features

- **Fix:**
  - fix title display on breadcrumb
  - fix JS media popin
- **New:**
  - uses now the Wordpress Media Uploader
  - uses now the Wordpress Color field
  - add information in function comments
  - add admin warning messages
  - add Instagram social button


## v1.0.0 (October 30, 2012)

- **Initial release**