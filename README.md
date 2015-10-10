# remotecp-plugin-restart
### Lets you restart services on your system from within remoteCP

*****
![Restart Plugin in Action](https://i.imgur.com/1smLHa8.png)

English and German translations are included.

#### Installation
* Copy the directory `Restart/` into the `plugins/` directory of your remoteCP installation
* Add `<plugin>Restart</plugin>` to your `xml/settings/<settingset>/settings.xml`
* Set up any services you want in the `plugins/Restart/settings.xml` file. Remember that you can have different settings for each server by copying your `settings.xml` file to `settings_<serverid>.xml`
* To let users see the status of the services grant the permission `offlinelogin`
* To let them start, stop and restart the services grant the permission `maintenance`
* If you need to, these permissions can be adjusted in the `index.php` of this plugin by settings different values for
	* `$vpermissions` (Permission to view the status of the services)
	* `$apermissions` (Permission to start/stop/restart the services)
* Set up the restart script (Read more below)

#### Usage
Self-explanatory.

#### Requirements
* No special PHP version or extensions needed. It will run on any PHP setup remoteCP runs on
* Access to the exec()-function. On a default PHP installation you'll be fine, but many hosters block this for security reasons
* A script that handles the actual operations on the services. Read more below

#### Restart script
The plugin needs a script that handles the actual operations (start, stop, restart and status).
It must adhere to the [LSB 5.0 Chapter 22.2](http://refspecs.linuxfoundation.org/LSB_5.0.0/LSB-Core-generic/LSB-Core-generic/iniscrptact.html). That way, if you already have conforming init scripts for the services, you can pass their exit codes and output through. Output of the script will be shown in the webinterface only in case of an error (that is, exit code ≠ 0).

A proposal of this script for Linux systems is included (`userrestart.sh`). It relies on the `sudo` and `service` commands and has been tested on Debian 8, but should work on any system offering these two commands. If you intend to use it you have to do the following steps to make it work:
1. Copy the script to a suitable location, for example `/usr/local/bin/` (this is the expected default)
2. Make sure the mode of the file allows execution
3. Create the group `userrestart`
4. Add the user running your remoteCP PHP files into the group `userrestart`
5. Set up a custom sudoers rule, letting members of the group `userrestart` run the script as root without password prompts

I didn't include any commands on purpose because you **must** understand what you are doing when dealing with sudo instead of just copy-pasting commands from the internet.

**Please note that using this script as-is introduces security issues,** because members of the group `userrestart` can start, stop and restart **ANY** service on your system. If possible, introduce further security measures by letting the script refuse to operate on any other services than you intend.

If you are an experienced system administrator you're probably better off writing your own script anyway. You can specify the path to your script in the plugin's `settings.xml`.

#### License
remotecp-plugin-restart is licensed under the GNU General Public License Version 3. You should have received a copy of the GNU General Public License along with this program (See `LICENSE`). If not, see [http://www.gnu.org/licenses/](http://www.gnu.org/licenses/).
