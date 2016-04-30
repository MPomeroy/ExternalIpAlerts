# ExternalIpAlerts
A set of PHP daemons to alert a user to changes to the external IP address of a machine.

The mailExternalIp script sends an email (to an address specified in the configuration file), when the external IP address of the host computer changes. The email includes the new IP address and the timestamp that the IP was checked at. A name for the external IP address must be specified in the config file ("External IP" by default); this is used to identify which IP address has been updated if the user has more than one daemon running.

The uploadExternalIp script uploads a text file to an ftp server (URL, credentials and storage path must also be specified in the appropriate configuration file).

The scripts use independent configuration files, empty configuration files for both scripts are included in the repository.
