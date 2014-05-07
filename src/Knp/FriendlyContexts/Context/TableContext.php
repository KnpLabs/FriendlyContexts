<?php

namespace Knp\FriendlyContexts\Context;

use Knp\FriendlyContexts\Utils\TextFormater;
use Knp\FriendlyContexts\Utils\Asserter;
use Behat\Gherkin\Node\TableNode;

class TableContext extends RawMinkContext
{
    /**
     * @Then /^I should see a table with "([^"]*)" in the "([^"]*)" column$/
     */
    public function iShouldSeeATableWithInTheNamedColumn($list, $column)
    {
        $expected = array_merge(array($column), $this->getFormater()->listToArray($list));

        $this->iShouldSeeTheFollowingTable(array($expected));
    }

    /**
     * @Given /^I should see the following table:?$/
     */
    public function iShouldSeeTheFollowingTable($expected)
    {
        if ($expected instanceof TableNode) {
            $expected = $expected->getTable();
        }

        $this->iShouldSeeATable();

        $tables = $this->findTables();
        $exceptions = array();

        foreach ($tables as $table) {
            try {
                if (false === $extraction = $this->extractColumns(current($expected), $table)) {
                    $this->getAsserter()->assertArrayEquals($expected, $table, true);

                    return;
                }
                $this->getAsserter()->assertArrayEquals($expected, $extraction, true);

                return;
            } catch (\Exception $e) {
                $exceptions[] = $e;
            }
        }

        $message = implode("\n", array_map(function ($e) { return $e->getMessage(); }, $exceptions));

        throw new \Exception($message);
    }

    /**
     * @Then /^I should see the following table portion:?$/
     */
    public function iShouldSeeTheFollowingTablePortion($expected)
    {
        if ($expected instanceof TableNode) {
            $expected = $this->reorderArrayKeys($expected->getTable());
        }

        $this->iShouldSeeATable();

        $tables = $this->findTables();
        $exceptions = array();

        foreach ($tables as $table) {
            try {
                if (false === $extraction = $this->extractColumns(current($expected), $table)) {
                    $this->getAsserter()->assertArrayContains($expected, $table);

                    return;
                }
                $this->getAsserter()->assertArrayContains($expected, $extraction);

                return;
            } catch (\Exception $e) {
                $exceptions[] = $e;
            }
        }

        $message = implode("\n", array_map(function ($e) { return $e->getMessage(); }, $exceptions));

        throw new \Exception($message);
    }

    /**
     * @Then /^I should see a table with ([^"]*) rows$/
     * @Then /^I should see a table with ([^"]*) row$/
     */
    public function iShouldSeeATableWithRows($nbr)
    {
        $nbr = (int) $nbr;

        $this->iShouldSeeATable();
        $exceptions = array();
        $tables = $this->getSession()->getPage()->findAll('css', 'table');

        foreach ($tables as $table) {
            try {
                if (null !== $body = $table->find('css', 'tbody')) {
                    $table = $body;
                }
                $rows = $table->findAll('css', 'tr');
                $this->getAsserter()->assertEquals($nbr, count($rows), sprintf('Table with %s rows expected, table with %s rows found.', $nbr, count($rows)));
                return;
            } catch (\Exception $e) {
                $exceptions[] = $e;
            }
        }

        $message = implode("\n", array_map(function ($e) { return $e->getMessage(); }, $exceptions));

        throw new \Exception($message);
    }

    /**
     * @Then /^I should see a table$/
     */
    public function iShouldSeeATable()
    {
        try {
            $this->getSession()->wait(2000, '0 < document.getElementsByTagName("TABLE").length');
        } catch (\Exception $ex) {
            unset($ex);
        }
        $tables = $this->getSession()->getPage()->findAll('css', 'table');
        $this->getAsserter()->assert(0 < count($tables), 'No table found');
    }

    protected function extractColumns(array $headers, array $table)
    {
        if (0 == count($table) || 0 == count($headers)) {
            return false;
        }

        $columns = array();
        $tableHeaders = current($table);
        foreach ($headers as $header) {
            $inArray = false;
            foreach ($tableHeaders as $index => $thead) {
                if ($thead === $header) {
                    $columns[] = $index;
                    $inArray = true;
                }
            }
            if (false === $inArray) {
                return false;
            }
        }

        $result = array();
        foreach ($table as $row) {
            $node = array();
            foreach ($row as $index => $value) {
                if (in_array($index, $columns)) {
                    $node[] = $value;
                }
            }
            $result[] = $node;
        }

        return $result;
    }

    protected function findTables()
    {
        $tables = $this->getSession()->getPage()->findAll('css', 'table');
        $result = array();

        foreach ($tables as $table) {
            $node = array();
            if (0 !== count($table->findAll('css', 'thead')) + count($table->findAll('css', 'tbody'))) {
                if (null !== $head = $table->find('css', 'thead')) {
                    $this->extractDataFromPart($head, $node);
                }
                if (null !== $body = $table->find('css', 'tbody')) {
                    $this->extractDataFromPart($body, $node);
                }
            } else {
                foreach ($table->findAll('css', 'tr') as $row) {
                    $node[] = $this->extractDataFromRow($row);
                }
            }
            $result[] = $node;
        }

        return $result;
    }

    protected function extractDataFromPart($part, &$array)
    {
        foreach ($part->findAll('css', 'tr') as $row) {
            $array[] = $this->extractDataFromRow($row);
        }
    }

    protected function extractDataFromRow($row)
    {
        $result = array();
        $elements = array_merge($row->findAll('css', 'th'), $row->findAll('css', 'td'));

        foreach ($elements as $element) {
            $result[] = preg_replace('!\s+!', ' ', $element->getText());
        }

        return $result;
    }

    protected function reorderArrayKeys(array $subject)
    {
        $orderedArray = array();

        foreach ($subject as $key => $value) {
            if (is_int($key)) {
                $orderedArray[] = $value;
            } else {
                $orderedArray[$key] = $value;
            }
        }

        return $orderedArray;
    }

    protected function getAsserter()
    {
        return new Asserter($this->getFormater());
    }

    protected function getFormater()
    {
        return new TextFormater;
    }
}
