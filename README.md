# wp-cf7-log #

Wordpress plugin to write log messages on `Contact Form 7` (`CF7`) submission
actions, mainly aiming to facilitate integration with `fail2ban`.

## Overview ##

This is a tiny module that writes a message to `syslog` file ( using `LOG_DAEMON`
facility) on each `Contact Form 7` submission.

The log message looks like:

```
Jul 25 22:55:28 xz wpcf7log[20374]: contact form 7 submission from 10.0.0.10
```

It can be matched by the `fail2ban` filter:

```
failregex = ^%(__prefix_line)scontact form 7 submission from <HOST>$
```

## Installation ##

Deploy the folder with the plugin to your `wordpress/wp-content/plugins/`, then
go to the web admin interface of `wordpress` and enable the `wp-cf7-log` plugin.

The plugin can be downloaded using `git`:

```
git clone https://github.com/miconda/wp-cf7-log
```

Or with `wget`:

```
wget https://github.com/miconda/wp-cf7-log/archive/master.zip
unzip master.zip
mv wp-cf7-log-master wp-cf7-log
rm master.zip
```

Now you have the `wp-cf7-log` folder containing the plugin, move it to wordpress
plugins directory (e.g., `/var/www/wordpress/wp-content/plugins/`).

Once the plugin is activated from the web administration interface, on each
`Contact Form 7` form submission, a log message is printed to syslog file.

The syslog file can be:

  * `/var/log/syslog` - for Debian/Ubuntu/... distributions
  * `/var/log/messages` - for RedHat/CentOS/Fedora/.. distributions

## Fail2Ban Integration ##

Fail2ban is a popular application that can be used to automatically block IP
addresses based on regular expression matching of log messages. The log message
has to include the IP address, then one can specify jailing rules like how
many occurrences to match and for how long to block the IP address.

No matter how many anti-spam and anti-bot layers one sets for `wordpress` and
`contact form 7` (e.g., captcha, anti-spam, quiz, ...), they can still be
worked around (e.g., by using cheap human labour for submitting via web contact
forms). A matter of what is the wordpress portal used for and the purpose of
the contact form, it may make sense to restrict the number of contact form
submissions per IP address for a defined interval of time.

Using the `wp-cf7-log` module together with `fail2ban`, one can restrict contact
form submissions using policies like: `maximum 5 submissions per IP per 60 minutes`,
or `1 submission per IP per 10 minutes`. It is what a typical company targets
with a contact form made available for prospective customers that want to ask
details about the services and products of the company.

Note that the fail2ban integration is global per server, no matter how many
contact forms you have and how many wordpress portals are running on the same
server.

### Deploy Fail2Ban Configs ###

In the `fail2ban/` subfolder in the plugin directory are located the jail and
filter files needed to integrate with `fail2ban` application.

They can be deployed with the following commands:

```
cd wp-cf7-log
cp fail2ban/jail.d/wp-cf7-log.conf /etc/fail2ban/jail.d/
cp fail2ban/filter.d/wp-cf7-log.conf /etc/fail2ban/filter.d/
```

Edit `/etc/fail2ban/jail.d/wp-cf7-log.conf` and adjust the values to match better
your needs. It may also require the update of `logpath` to `/var/log/messages`
in case the deployment is on a RedHat/CentOS/Fedora/OpenSuse/... system.

Restart `fail2ban`:

```
systemctl restart fail2ban
```

The `fail2ban-client status` should now list the `wp-cf7-log` jail, like:

```
# fail2ban-client status
Status
|- Number of jail:	4
`- Jail list:	sshd, wp-cf7-log
```

Note: if the distribution does not support split jail files for `fail2ban`,
then just add the content of `fail2ban/jail.d/wp-cf7-log.conf` to
`/etc/fail2ban/jail.local`.

## Development ##

The goal is to keep this module minimal, but in the future a couple of enhancements
may be added:

  * web ui admin interface to set the log message content
  * several log messages to print also a few more details from the submitted form
  (e.g., email address of submitter, subject)

### Contributions ###

The preferred way is to make pull requests at:

  * https://github.com/miconda/wp-cf7-log/pulls

## License ##

GPL v2.0