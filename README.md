<a href="https://travis-ci.org/catalyst/moodle-tool_hashlegacy">
<img src="https://travis-ci.org/catalyst/moodle-tool_hashlegacy.svg?branch=master">
</a>

# tool_hashlegacy

A tool to check user password hash algorithms.

* [What is this?](#what-is-this)
* [Branches](#branches)
* [Installation](#installation)
* [Usage](#usage)
* [Support](#support)

What is this?
-------------

This is a tool that performs a simple report on the hash algorithm being used to store user passwords. Currently it has support for SHA-512, SHA-256, Bcrypt (blowfish), and MD5. It shows counts of users using each algorithm type.

It also allows for batch force password changes for anyone on a particular algorithm, by interfacing with the bulk user actions module. This will quickly allow for forcing all users on an insecure algorithm to have a new password generated on a secure algorithm.

Branches
--------

| Moodle verion     | Branch      | PHP  |
| ----------------- | ----------- | ---- |
| Moodle 3.5+       | master      | 7.0+ |


Installation
------------

Install the plugin by using git to clone the plugin into your Moodle source:

```sh
   git clone git@github.com:catalyst/moodle-tool_hashlegacy.git admin/tool/hashlegacy
```

The run the Moodle upgrade.
This plugin requires no configuration.

Usage
-----

To use this plugin, simply visit the site reports section of Site administration at Site Administration->Reports->Legacy Hash Report.
Once the table is generated with information on all the user hash algorithms, they are displayed, aggregated by algorithm. To perform a
forced password change on a batch of users on a particular algorithm, simply click the link in the action column. Once this action is confirmed, the action will be performed, and you will be redirected to the Bulk user actions page.

Support
-------

If you have issues please log them in github here

https://github.com/catalyst/moodle-tool_hashlegacy/issues

Please note our time is limited, so if you need urgent support or want to sponsor a new feature then please contact us:

https://www.catalyst-au.net/contact-us

This plugin was developed by Catalyst IT Australia:

https://www.catalyst-au.net/

<img alt="Catalyst IT" src="https://cdn.rawgit.com/CatalystIT-AU/moodle-auth_saml2/master/pix/catalyst-logo.svg" width="400">
