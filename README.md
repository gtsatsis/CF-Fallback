#### CF-Fallback
##### What is CF-Fallback/Failover/Failsafe?

CF-Fallback is a PHP script that will detect whenever your origin is down and swap out the IPs on the DNS level automatically (via cron).

It is heavily Work-In-Progress, but the current release has support for one DNS record & every Cloudflare error (It currently only looks at the Cloudflare status, not the origin server)

TODO:

* Add multi-backup (Round-robin)
* Add origin & backup server checks (instead of hitting Cloudflare and figuring it out from CF's errors.)
* Document the source code

