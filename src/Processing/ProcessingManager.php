<?php

namespace Application\Processing;

/**
 * Class ProcessingManager
 * @package Application\Processing
 */
class ProcessingManager
{
    /** @var  DataImportInterface */
    protected $dataImport;
    /** @var  DataParserInterface */
    protected $dataParser;
    /** @var  DataExportInterface */
    protected $dataExport;

    /**
     * @return DataImportInterface
     */
    public function getDataImport()
    {
        return $this->dataImport;
    }

    /**
     * @param DataImportInterface $dataImport
     */
    public function setDataImport(DataImportInterface $dataImport)
    {
        $this->dataImport = $dataImport;
    }

    /**
     * @return DataParserInterface
     */
    public function getDataParser()
    {
        return $this->dataParser;
    }

    /**
     * @param DataParserInterface $dataParser
     */
    public function setDataParser(DataParserInterface $dataParser)
    {
        $this->dataParser = $dataParser;
    }

    /**
     * @return DataExportInterface
     */
    public function getDataExport()
    {
        return $this->dataExport;
    }

    /**
     * @param DataExportInterface $dataExport
     */
    public function setDataExport(DataExportInterface $dataExport)
    {
        $this->dataExport = $dataExport;
    }

    /**
     * @param array $where
     */
    public function execute(array $where)
    {

    }

}