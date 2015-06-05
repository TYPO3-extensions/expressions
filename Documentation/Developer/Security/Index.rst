.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _developer-security:

Security concerns
^^^^^^^^^^^^^^^^^

This library makes it possible to tap GET and POST variables. The
safety of these values can never be ascertained. The exact risks
depend on where this library is used. Care must be taken – once values
are retrieved using the expressions library – to sanitize those values
in an appropriate manner depending on how they are used (e.g. XSS, SQL
injection, etc.). The functions that can be called on each expression
provide some safety against this.

When expressions are used inside an extension, it is also up to each
developer to judge what additional security to implement.
