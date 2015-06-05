.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _installation:

Installation
------------

Just install the extension and start using it. As of version 2.0.0, TYPO3 CMS 6.2 or newer is required.


.. _installation-update:

Updating to version 2.0
^^^^^^^^^^^^^^^^^^^^^^^

With version 2.0, all PHP classes were changed to use namespaces.
There is no backward compatibility layer. So if you used code like:

.. code:: php

	$foo = tx_expressions_parser::evaluateExpression('gp:bar');

you must now use:

.. code:: php

	$foo = \Cobweb\Expressions\ExpressionParser::evaluateExpression('gp:bar');
