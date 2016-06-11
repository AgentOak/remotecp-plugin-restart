# remotecp-plugin-restart
### Lets you restart services on your system from within remoteCP

*****
![Restart Plugin in Action](https://i.imgur.com/1smLHa8.png)

English and German translations are included.

#### Installation
* Copy the directory `Restart/` into the `plugins/` directory of your remoteCP installation
* Add `<plugin>Restart</plugin>` to your `xml/settings/<settingset>/settings.xml`
* Set up any services you want in the `plugins/Restart/settings.xml` file
	* You need to specify a command for every service. Read more below

#### Usage
* To let users see the status of the services grant the permission `offlinelogin`
* To let them start, stop and restart the services grant the permission `maintenance`
* If you need to, you can adjust these permissions in the `index.php` of this plugin by settings different values for
	* `$vpermissions` (Permission to view the status of the services)
	* `$apermissions` (Permission to start/stop/restart the services)

#### Requirements
* No special PHP version or extensions needed. It will run on any PHP setup remoteCP runs on
* Access to the exec()-function
	* On a default PHP installation you'll be fine, but many hosters block this for security reasons
* Commands which restart the services (Read more below)

#### Service commands
For each service you have to specify a command that handles the actual operations (start, stop, restart and status).
It must adhere to the [LSB 5.0 Chapter 22.2](http://refspecs.linuxfoundation.org/LSB_5.0.0/LSB-Core-generic/LSB-Core-generic/iniscrptact.html). That way, if you already have conforming init scripts for the services, you can just call them and pass their exit codes and output through. Output will be shown in the webinterface only in case of an error (that is, exit code ≠ 0).

As you usually need root privileges to use the init scripts, a script is included to help with this (`userrestart.sh`). It relies on the `sudo` and `service` commands and has been tested on Debian 8, but should work on any system offering these two commands. You can use it like this:

1. Copy `userrestart.sh` to a suitable location that is in your webservers `$PATH`, `/usr/local/bin/` should do
2. Make sure the mode of the file is set to allow execution (Your usual `chmod +x`)
3. In `userrestart.sh`, modify line 6 to include all the services you want the plugin to operate on (this is an additional security measure)
4. Set up a sudoers rule, letting the user your webserver is running as (usually `www-data`) execute the script as root without password prompts

The `settings.xml` can be left as is (except for the names of the services) since it is preconfigured to work with the `userrestart.sh` script.

I didn't include any commands on purpose because you **must** understand what you are doing when dealing with sudo instead of just copy-pasting commands from the internet.

#### Troubleshooting
Feel free to open an issue on this project.

#### License
remotecp-plugin-restart is licensed under the GNU General Public License Version 3. You should have received a copy of the GNU General Public License along with this program (See `LICENSE`). If not, see [http://www.gnu.org/licenses/](http://www.gnu.org/licenses/).
