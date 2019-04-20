#!/bin/sh
TO=novembre1998@libero.it

# -i  - do not treat special lines starting with "."
# -v  - use verbose mode (provide SMTP session transcript)
# -Am - use sendmail.cf (do not send via localhost:25) - requires root privileges
/usr/sbin/sendmail -i -v -Am -- $TO <<END
Subject: Delivery test
From: noreply@musetek.ml
To: $TO


Delivery test.
END
