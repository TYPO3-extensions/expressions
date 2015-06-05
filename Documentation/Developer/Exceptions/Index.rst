.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _developer-exceptions:

Exceptions
^^^^^^^^^^

Method :code:`evaluateExpression()` may throw a number of
exceptions. The :code:`evaluateString()` method will catch them, but
if you call :code:`evaluateExpression()` directly you will have to
take care of these exceptions.

All exceptions will be of type :code:`\Cobweb\Expressions\Exception\Exception`.
