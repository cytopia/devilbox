.. _getting_started_email_catch_all:

***************
Email catch-all
***************

All your projects can send emails to whatever recipient. You do not have to worry that they will
actually being sent. Each PHP container runs a local postfix mailserver that intercepts
all outgoing mails and puts them into a local mailbox by the user ``devilbox``.

In order to view sent emails open up the devilbox intranet http://localhost/mail.php.
There you can also test email sending and verify that they really stay locally.

.. image:: /_static/img/devilbox-email-catch-all.png

In the above image from the intranet you can see that all emails sent to whatever recipient
have been caught by the Devilbox and are available to be read.
