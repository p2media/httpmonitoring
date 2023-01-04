# TYPO3 Extension `httpmonitoring`

## Description

This extension allows you to keep track of the HTTP status returned by different
websites.

A new module is added to the tools main module. Inside it, you can define
sites, which are collections of URIs and addresses. Every URI (e.g. example.com)
you define inside a site will have its status monitored, and every address that
is part of the same site will be notified if a status changes.

You can also use the dropdown in the module header to view a list of the most
recent logs, and filter them by status and creation date timespan using the
filter form.

## Configuration

There are two values in the extension configuration under the settings module
which you may want to change:
* Maximum log age in seconds : Logs older than this are deleted by the
httpmonitoring:deleteoldlogs command.
* Default sender address: Status update emails are sent from this address.

--------------------------------------------------------------------------------

The commands from the commands section should be set to automatically
execute on the desired schedule via the scheduler backend module.

## Commands

* httpmonitoring:checkstatus : When this command is called, it will check the
  status of every saved URI. If some of them start returning error codes,
  or if some previously returned error codes but now return acceptable
  codes, every email address saved in the same site as the URI in question
  will be notified via an email.
* httpmonitoring:deleteoldlogs : When this command is called, every log that
  is older than the 'maximumLogAge' extension configuration value will be
  deleted.
