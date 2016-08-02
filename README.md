# [Nexcess.net](https://www.nexcess.net/) Alarmbell Extension for Magento

Alarmbell is a utility extension for [Magento](https://www.magentocommerce.com/) 
that provides logging and email notifications when admin users are created, edited 
or deleted. It can also be configured to provide notifications of admin account logins.

Often the first thing an attacker does upon gaining access to a Magento
store is create an admin user, often with the intention of installing their own 
extensions or otherwise further compromising the store. With Alarmbell users will
have some warning that such actions are occurring on their store.


## Features

  * Actions affecting admin user records (create/edit/delete) are logged to the standard *system.log* and record the IP and account info
of the user making the changes.
  * All admin user logins (including failed attempts) are logged to the standard *system.log* (with IP and username)
  * Can be enabled/disabled.
  * Can also be configured to send customized email alerts.


## Installation & Usage

  * Download and install the tarball package from the Downloads page on GitHub (note that this is not the "Download as tar.gz" button) and install through Magento Connect Downloader or Magento's mage command.
  * Install with modman. You would just need to use: modman clone https://github.com/nexcess/magento-alarmbell


## Support

Please feel free to open issues via the extension's Github page! 

## Contributing

If you have a fix or feature for Alarmbell, submit a pull request through GitHub
to the **devel** branch. The *master* branch is only for stable releases. Please
make sure the new code follows the same style and conventions as already written
code.

## License

The code is licensed under GPLv2+.
