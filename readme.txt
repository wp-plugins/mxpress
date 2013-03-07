=== Plugin Name ===
Contributors: clemeric, AndreSC
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=KAEWEBH8R7JM2&lc=ZA&item_name=Kazazoom&item_number=donmxp1&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: mxpress,mxit,wordpress,mobi,code,dev,app
Requires at least: 3.5
Tested up to: 3.5.1
Stable tag: 1.0.3
License: BSD-3 license
License URI: http://opensource.org/licenses/BSD-3-Clause

The mxPress Plugin creates a Mxit comptaible version of your WordPress site.

== Description ==

Get your WordPress Blog or Website hosted as a Mxit App. Or create a new Blog in WordPress and host it as a funky new app in Mxit!

= What is Mxit? =

Mxit started in 2006 as a chat client for feature phones which allowed users to send messages to each other at low cost (using the data available on phones, rather than SMS)
Since then, Mxit has grown to become the largest social network in South Africa, with 9.3 million active users measured in a 12 week period up to the end of June 2012.
Users on Mxit now not only send direct messages to each other, but also use Mxit to access services which include chat rooms, games like chess, and use 3rd party Mxit services. This includes Kazazoom services such as Ask.Kim.


= What does this plugin do? =

In a nutshell, the mxPress WordPress Plugin allows anyone (even non-developers) who are currently running a Blog, Website, or Mobi Site using WordPress, to automatically show their WordPress site on Mxit as a contact.

As an example, invite wpmx on Mxit and see the http://www.mxpress.co.za WordPress site as a Mxit Contact on the Mxit platform. 

Typically, to create a Mxit App you would need to code an App from scratch using the Mxit C# API or Mxit Mobi API. With this plugin, you do not need to do any development to get your existing (or new) WordPress site integrated with Mxit.
Mxit has opened up their platform to 3rd party developers which means anybody, yes even you can host a Mxit App on the Mxit Platform, read more at the Mxit Developer Site.

mxPress also contains a variety of configuration values that allow you to decide how you want your WordPress site to look on it's Mxit version. Some features include:
- Load your company logo as a front-page banner, the plugin auto scales it for the users screen size.
- Use images in your posts and pages. mxPress will scale and cache the images for use on Mxit based on the user's screen size
- Use WordPress template files to customize your Mxit Portal's layout.
- Define a custom menu using the WordPress menu manager to create your Mxit Menu
- Show your latest posts on your Mxit landing page
- Integration with Mxit's approved advertising network, Shinka for showing banner ads.  Just simply add your ad unit ID's on the mxPress Mxit Settings page.
- User's can now comment on posts from your Blog via Mxit!
- Paging built in to allow for easy navigation of Posts as well as comments on posts.
- Integration with Google Analytics tracking.

== Installation ==

= How do I install the plugin? =

- Simple open your Plugins page on your WordPress Admin Page, and search for "mxPress" and click on install. 
- Then click on 'activate'.
- Go to the 'Appearance / Mxit Settings' page, and configure either your Front-Page or Home Page, depending on whether you have your WordPress theme configured to show a static frontpage or the default homepage.
- We recommended you create a new Menu in the WordPress Menu Manager called "Mxit Menu" with the pages and categories you want to show in Mxit, and then go to 'Appearance / Mxit Settings' to configure mxPress to show that Menu.

= What happens after I have installed the plugin? =

* Go to the Mxit Developer Site (http://dev.mxit.com) and register your Mxit App.
* Read more about the process in the Mxit App Getting Started Guide (http://dev.mxit.com/docs/mobi-portal-api/getting-started)
* Once you click 'Create Mobi Portal', you will be prompted for a URL. Simply enter the URL of your WordPress site.


== Frequently Asked Questions ==

= Where can I get more detailed information about registering a Mxit App?  =

- Read more about how to register a Mxit app on the Official mxPress Site (http://www.mxpress.co.za/installsteps/#section3)
- Or go straight to the Mxit Getting Started Guide on the Mxit Developer Site (http://dev.mxit.com/docs/mobi-portal-api/getting-started)


== Screenshots ==

1. A WordPress site displayed through mxPass on Mxit App 'kickoff'.
2. Find the MxPress admin configuration options under Appearance -> Mxit Options.
3. Possible administrator customization options include uploading/selecting specific logo to use on Mxit, specifying menus to use on different content templates and more...


== Changelog ==

= 1.0.3 =
Bug fix. 2013-01-30

- Fixed problem with the Plugin causeing a "Dont know what to do" page.

= 1.0.2 =
Bug fix. 2013-01-30

- Removed "xxx" characters from banner ads.
- Only show page links when there are more than one page.

= 1.0.1 =
Bug fix. 2013-01-29

Bug fix to make plugin only display Mxit version for Mxit requests, and not Web requests. If you know what you are doing, and want to always display the Mxit version change:
    if (($_SERVER['HTTP_X_MXIT_USERID_R'] || ($_mixitversion !== false) || ($_GET['debug_mxp'] == '1') ) // 
to
	if (($_SERVER['HTTP_X_MXIT_USERID_R'] || ($_mixitversion !== false) || ($_GET['debug_mxp'] == '1') || 1==1) // 
in functions_config.php
	
= 1.0.0 =
Public release of first production version. 2013-01-29

New features include:
- User's can now comment on posts/articles via Mxit!
- Load your company logo as a front-page banner, the plugin auto scales it for the users screen size.
- Use images in your posts and pages. mxPress will scale and cache the images for use on Mxit based on the user's screen size
- Use WordPress template files to customize your Mxit Portal's layout.
- Define a custom menu using the WordPress menu manager to create your Mxit Menu
- Show your latest posts on your Mxit landing page
- Integration with Mxit's approved advertising network, Shinka for showing banner ads.  Just simply add your ad unit ID's on the mxPress Mxit settings page.
- Paging built in to allow for easy navigation of Posts as well as comments on posts.

= 0.1.0 =
First production version. 2013-01-29

= 0.0.9 =
First version (beta). 2012-06-09


