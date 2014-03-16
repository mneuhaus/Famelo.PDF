Famelo.PDF
==========

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mneuhaus/Famelo.PDF/badges/quality-score.png?s=b6c22938502bc98132697e2b98b33429b3a05144)](https://scrutinizer-ci.com/g/mneuhaus/Famelo.PDF/)

This package provides a quick and simple way to generate a PDF from a Fluid Template through
the [MPDF](http://mpdf1.com) library

Example:

```php
$document = new \Famelo\PDF\Document('My.Package:SomeDocument');
$document->assign('someVariable', 'foobar');

// Trigger a Download and exit
$document->download('SomeDocument ' . date('d.m.Y') . '.pdf');

// Show the document inline and exit
$document->inline();
```

This example will render a template located at 'resource://My.Package/Private/Documents/SomeDocument.html
and convert it to PDF.
