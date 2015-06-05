.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _developer-view-helper:

Fluid View Helper
^^^^^^^^^^^^^^^^^

The extension comes with a view helper for Fluid. As curly braces
(:code:`{` and :code:`}`) are used as markers in Fluid, they must be escaped so as not to
break Fluid rendering.

Declaration of the namespace:

.. code:: text

	{namespace expression = Cobweb\Expressions\ViewHelpers}

Usage:

.. code:: html

	<expression:evaluate>Current page id is \{tsfe:id\}</expression:evaluate>

Result:

.. code:: text

	Current page id is 1

Usage (with inline notation):

.. code:: text

	{expression:evaluate(expression:'Current user is \{fe_user:username\}')}

Result:

.. code:: text

	Current user is zaphod
