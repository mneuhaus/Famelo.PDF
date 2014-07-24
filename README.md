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
$document->send();

// Save the document to a local file
$document->save('/Some/Path/SomeDocument ' . date('d.m.Y') . '.pdf');
```

This example will render a template located at 'resource://My.Package/Private/Documents/SomeDocument.html
and convert it to PDF.


Page Format and orientation
---------------------------

By default pages will be rendered as a A4 Portrait.
You can choose another format/orientation like this:

```php
// set format to A5 Portrait
$document = new \Famelo\PDF\Document('My.Package:SomeDocument', 'A5');

// set format to an A4 Landscape
$document->setFormat('A4-L');

// set format to 100mm x 200mm
$document->setFormat(array(100, 200));
```

The MPDF library supports different page sizes with these keywords or an array containing 2 values for width + height:

- A0 - A10
- B0 - B10
- C0 - C10
- 4A0
- 2A0
- RA0 - RA4
- SRA0 - SRA4
- Letter
- Legal
- Executive
- Folio
- Demy
- Royal
- Ledger
- Tabloid*

All of the above values can be suffixed with "-L" to force a Landscape page orientation document e.g. "A4-L".
If format is defined as a string, the final orientation parameter will be ignored.

*Ledger and Tabloid are standard formats with the same page size but different orientation (Ledger is landscape, and Tabloid is portrait). mPDF treats these identically; if you wish to use Ledger, you should specify "Ledger-L" for landscape.


PDF Generator Implementation
----------------------------

By default this Library uses the MPDF library to generate the PDFs.
But you can change the defaultGenerator through the Settings.yaml like
this:

```yaml
Famelo:
  PDF:
    # Generator using wkhtmltopdf through knplabs/knp-snappy
    DefaultGenerator: '\Famelo\PDF\Generator\WebkitGenerator'
    DefaultGeneratorOptions:
      Binary: '/usr/local/bin/wkhtmltopdf'
```

Feel free to create and use a generator for your favorite PDF Library
And send me a Pull-Request if you think others might like to use it :)