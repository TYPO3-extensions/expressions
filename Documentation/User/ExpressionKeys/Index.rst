.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _user-keys:

Expression keys reference
^^^^^^^^^^^^^^^^^^^^^^^^^

Expressions can be used both in the the frontend and the backend, but
some keys will obviously not be available in the backend as they rely
on frontend object (for example, :code:`$TSFE`).

It is possible to create processors for custom expression keys (see
"Developer's Guide") and thus to extend the expression parser.

- `config`_
- `date`_
- `extra`_
- `env`_
- `fe\_user`_
- `gp`_
- `page`_
- `plugin`_
- `session`_
- `strtotime`_
- `tsfe`_
- `vars`_

.. _user-keys-tsfe:

tsfe
""""

Gets a value from the :code:`$TSFE` global variable.

**Examples**

Retrieve the uid of the current page:

.. code:: text

	tsfe:id

Retrieve the username of the currently logged in user:

.. code:: text

	tsfe:fe_user|user|username


.. _user-keys-page:

page
""""

Gets a value related to the current page, as stored in :code:`$TSFE->page`.

**Example**

Retrieve the uid of the current page:

.. code:: text

	page:uid


.. _user-keys-config:

config
""""""

Gets a value from the "config" object, as stored in
:code:`$TSFE->config['config']`.

**Example**

Retrieve the current language:

.. code:: text

	config:language


.. _user-keys-feuser:

fe\_user
""""""""

Gets a value for the current frontend user, as stored in
:code:`$GLOBALS['TSFE']->fe_user->user`.

**Example**

Retrieve the current FE user's name:

.. code:: text

	fe\_user:name


.. _user-keys-plugin:

plugin
""""""

Get a TypoScript property for a plugin as stored in
:code:`$GLOBALS['TSFE']->tmpl->setup['plugin.']`.

.. note::

   Remember that TS indices have an ending dot (.).

**Example**

Retrieve the TypoScript property :code:`plugin.tx_vgetagcloud_pi1.startPage.data`:

.. code:: text

	plugin:tx_vgetagcloud_pi1.|startPage.|data


.. _user-keys-gp:

gp
""

Gets a value from either the :code:`$_GET` or :code:`$_POST` superglobal arrays.
This makes it possible to retrieve any GET or POST variable passed to the page.

**Example**

Retrieve the uid of a single news record:

.. code:: text

	gp:tx_ttnews|tt_news


.. _user-keys-vars:

vars
""""

Gets a value from :ref:`"internal" variables <developer-variables>` variables.
This depends on what is loaded here by the extension that users the parser.
This is meant to be equivalent to a traditional's plugin "piVars".

**Example**

Retrieve the "showUid" variable from the internal variables:

.. code:: text

	vars:showUid


.. _user-keys-extra:

extra
"""""

Same as above but for so-called :ref:`"external" variables <developer-variables>`.

.. note::

   This is done, for example, by the :ref:`context extension <context:start>`
   to make context values accessible.

**Example**

Retrieve the value of "category" from the context:

.. code:: text

	extra:category


.. _user-keys-date:

date
""""

Gets values related to the current time, using formats from the PHP
`date() <http://php.net/date>`_ function.

**Example**

Retrieve the current year (4 digits):

.. code:: text

	date:Y


.. _user-keys-strtotime:

strtotime
"""""""""

Gets a timestamp by interpreting a human-readable date, as per the
capacities of PHP's function `strtotime() <http://php.net/strtotime>`_.

**Examples**

Retrieve the timestamp corresponding to Jan 1, 2009:

.. code:: text

	strtotime:2009-01-01

Retrieve the timestamp corresponding to tomorrow, same time:

.. code:: text

	strtotime:tomorrow


.. _user-keys-session:

session
"""""""

Gets values from some structure stored in the temporary session (i.e.
"ses" and **not** "user").

The first item (after the "session:" key) has a special meaning. It
corresponds to the key that was used to store into the session. The
following indices are used normally.

**Example**

Retrieve index "bob" of array dummy stored into the session:

.. code:: text

	session:dummy|bob


.. _user-keys-env:

env
"""

Get values from the environment variables, via the use of
:code:`\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv()`.
Please refer to that method for available values.

.. note::

   :code:`\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv()` expects values to be uppercase.
   The expressions parser takes care of uppercasing any incoming value, so
   there's no need to worry about that.

**Example**

Retrieve the host name:

.. code:: text

	env:http_host
