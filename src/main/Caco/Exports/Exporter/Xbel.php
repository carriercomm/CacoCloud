<?php
namespace Caco\Exports\Exporter;

use Caco\Bookmark\Model\Bookmark;
use XMLWriter;

/**
 * Class Xbel
 *
 * Exports the given bookmarks as XBEL 1.0 xml string.
 *
 * @package Caco\Exports\Exporter
 * @author Guido Krömer <mail 64 cacodaemon 46 de>
 */
class Xbel implements IXmlExporter
{
    /**
     * @var Bookmark[]
     */
    protected $bookmarks;

    public function __construct(array $bookmarks = null)
    {
        $this->bookmarks = $bookmarks;
    }

    /**
     * @param \Caco\Bookmark\Model\Bookmark[] $bookmarks
     */
    public function setBookmarks(array $bookmarks)
    {
        $this->bookmarks = $bookmarks;
    }

    /**
     * @return \Caco\Bookmark\Model\Bookmark[]
     */
    public function getBookmarks()
    {
        return $this->bookmarks;
    }

    /**
    * Builds and returns the XBEL xml.
    *
    * @return string
    */
    public function buildXML()
    {
        $w = new XMLWriter;
        $w->openMemory();

        $w->startDocument('1.0', 'utf-8');

        $w->startElement('xbel');
        $w->writeAttribute('version', '1.0');

        $w->writeElement('title', 'CacoCloud bookmarks');

        foreach ($this->bookmarks as $bookmark) {
            $w->startElement('bookmark');
            $w->writeAttribute('href', $bookmark->url);
            $w->writeAttribute('added', date('Y-m-d', $bookmark->date));
            $w->writeElement('title', htmlentities($bookmark->name));
            $w->endElement(); //bookmark
        }

        $w->endElement(); //xbel
        $w->endDocument();

        return $w->outputMemory(true);
    }

    /**
     * Determines if the output should be downloadable in a browser.
     *
     * @return bool
     */
    public function isFile()
    {
        return true;
    }

    /**
     * Gets the desired filename for downloading via HTTP.
     *
     * @return string
     */
    public function getFileName()
    {
        return 'CacoCloudBookmarks.xbel';
    }
}