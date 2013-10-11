# Tea Theme Options


## Simple, easy to use and fully integrated Theme Options Plugin for Wordpress.

The [Tea Theme Options](https://github.com/Takeatea/tea_theme_options) (or **Tea TO**) allows you to easily add professional looking theme options panels to your WordPress theme. The **Tea TO** is built for [Wordpress](http://wordpress.org "CMS Wordpress") v3.x and uses the Wordpress built-in pages.

+ [**Options API**](http://codex.wordpress.org/Options_API) - A simple and standardized way of storing data in the database.
+ [**Transients API**](http://codex.wordpress.org/Transients_API) - Very similar to the Options API but with the added feature of an expiration time, which simplifies the process of using the wp_options database table to temporarily store cached information.
+ **Wordpress Media Manager** - Beautiful interface: A streamlined, all-new experience where you can create galleries faster with drag-and-drop reordering, inline caption editing, and simplified controls.
+ **Full of Options** - 4 kinds of options used to display information, store fields values or get data from your Wordpress database. The options are made to build your Wordpress pages easily.
+ **Easier for administrators** - The interface is thought to be the most userfriendly. The Tea TO core adds some extra interface customisations to make your life easier.
+ **Easier for developers** - Create a new admin panel easily with the new dashboard. The Tea TO core is made to allow non-developer profiles to easily create the settings they need to customise their templates.
+ **Custom Post Types** - Create a custom post type from your uniq dashboard easily.  


## Summary

+ [1) Installing the plugin](#1-installing-the-plugin)
+ [2) Enable your Tea Theme Options plugin](#2-enable-your-tea-theme-options-plugin)
+ [3) Let's roll!](#3-lets-roll)
+ [4) Adding contents](#4-adding-contents)
+ [5) Previews](#5-previews)
+ [6) That's all folkes!](#6-thats-all-folkes)
+ [7) Authors](#7-authors)
+ [8) Copyright and license](#8-copyright-and-license)


### 1) Installing the plugin

**To get started**, checkout or download the https://github.com/takeatea/tea_theme_options package into the `wp-content/plugins/`

    git clone https://github.com/takeatea/tea_theme_options

Check your new `tea_theme_options` folder is created in your plugins directory.  


### 2) Enable your Tea Theme Options plugin

Go to your plugins page `your_wp_website/wp-admin/plugins.php` and click on the **Enable** button.


### 3) Let's roll!

Create your new first page settings (as capability, description and submit button).  
Click on the **Tea T.O.** page, select the **Add a custom page** menu and fill the form.  
NOTA: all created pages will only appear if contents are defined.

Repeat the process as you want/need :)
You can easily create Custom post types from the **Add a custom post type** menu: as usual, just fill the form.  


### 4) Adding contents

Adding fields is quite simple. All you have to do from the **Tea T.O.** page is to click on your wanted page and use the bottom Adding contents form. For each field, just follow the instructions and fill the form simply.  
NOTA: the **Tea TO** uses [Transient Wordpress API](http://codex.wordpress.org/Transients_API) to stock options.

All available types are:

**Display fields**:
+ Breakline or Horizontal rule - Can be usefull.
+ Heading - Display a simple title.
+ Paragraphe - A simple text content.
+ List items - Show items in an unordered list.
+ Features - **Special field** used only to build this documentation page (but you can use it as well).

**Common fields**:
+ Basic Text - The most basic of form fields. Basic, but important.
+ Email, number and more - The most basic of form fields extended. You can choose between email, password, number, range, search and url.
+ Hidden field - A hidden field, if you need to store a special data.
+ Textarea - Again basic, but essencial.
+ Checkbox - No need to introduce it...
+ Radio - Its brother (or sister, as you want).
+ Select - Provide a list of possible option values.
+ Multiselect - The same list as previous one but with multichoices.

**Special fields**:
+ Background - Great for managing a complete background layout with options.
+ Color - Need some custom colors? Use the Wordpress color picker.
+ Google Fonts - Want to use a custom font provided by Google Web Fonts? It's easy now.
+ Include - Offers the possibility to include a php file.
+ RTE - Want a full rich editing experience? Use the Wordpress editor.
+ Social - Who has never needed social links on his website? You can manage them easily here.
+ Wordpress Upload - Upload images (only for now), great for logo or default thumbnail. It uses the [Wordpress Media Manager](http://codex.wordpress.org/Version_3.5#Highlights).

**Wordress fields**:
+ Categories - Display a list of Wordpress categories.
+ Menus - Display a list of Wordpress menus.
+ Pages - Display a list of Wordpress pages.
+ Posts - Display a list of Wrdpress posts.
+ Post Types - Display a list of Wordpress posttypes.
+ Tags - Display a list of Wordpress tags.

**Social Networks fields**:
+ Flickr - Make a bridge between your website and your Flickr profile.
+ Instagram - Make a bridge between your website and your Instagram profile.
+ Twitter - Make a bridge between your website and your Twitter profile due to the new API v1.1.


### 5) Previews

Dashboard  
![Main dashboard](http://plugins.svn.wordpress.org/tea-theme-options/assets/screenshot-1.jpg)  
![Main empty dashboard](http://plugins.svn.wordpress.org/tea-theme-options/assets/screenshot-2.jpg)  

Page  
![Add a page](http://plugins.svn.wordpress.org/tea-theme-options/assets/screenshot-3.jpg)
![Edit a page](http://plugins.svn.wordpress.org/tea-theme-options/assets/screenshot-4.jpg)  

All fields  
![All fields listed](http://plugins.svn.wordpress.org/tea-theme-options/assets/screenshot-5.jpg)  

Specials  
![A connection block](http://plugins.svn.wordpress.org/tea-theme-options/assets/screenshot-6.jpg)  
![An example page](http://plugins.svn.wordpress.org/tea-theme-options/assets/screenshot-7.jpg)  


### 6) That's all folkes!

Here is the latest step: check quickly your new panel options.

+ Go to your `your_wp_website/wp-admin`
+ Log in to your admin panel
+ **See that you have a new Link in your admin sidebar**

That's all to begin working with **Tea TO**


### 7) Authors

**Take a Tea**

+ http://takeatea.com
+ http://twitter.com/takeatea
+ http://github.com/takeatea

**Achraf Chouk**

+ http://fr.linkedin.com/in/achrafchouk/
+ http://twitter.com/crewstyle
+ http://github.com/crewstyle


### 8) Copyright and license

Copyright 2013 [Take a tea](http://takeatea.com "Take a tea")  
Infusé par Take a tea ;)
