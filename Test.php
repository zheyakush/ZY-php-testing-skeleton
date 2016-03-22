<?php
require_once "./Spyc.php";

/**
 * Class Test
 */
class Test
{
    /**
     * Class for error answer
     */
    const ANSWER_WRONG = "red";
    /**
     * Class for success answer
     */
    const ANSWER_CORRECT = "green";
    /**
     * Class for missed answer
     */
    const ANSWER_MISSED = "blue";

    /**
     * Directory with tests files
     */
    const DIR = './test/';

    /**
     *
     */
    const MODE_REVIEW = "review";

    /**
     *
     */
    const MODE_TEST = "test";

    /**
     * List of available courses
     * @var array
     */
    protected $_availableCourses = [];

    /**
     * @var string
     */
    protected $_activeCourse = null;

    /**
     * @var array
     */
    protected $_courseData = [];

    /**
     * @var array
     */
    protected $_currentStep = 1;

    /**
     * @var int
     */
    protected $_currIndexQuestion = 0;

    /**
     * @var null
     */
    protected $_answer;

    /**
     * @var array
     */
    protected $_order = [];

    /**
     * @var int
     */
    protected $_countQuestion;

    /**
     * @var string
     */
    protected $_mode = "test";

    /**
     * @return array
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * @return array
     */
    public function getCurrentStep()
    {
        return $this->_currentStep;
    }


    /**
     * @return string
     */
    public function getMode()
    {
        return $this->_mode;
    }

    /**
     * @param string $mode
     */
    public function setMode($mode)
    {
        $availableModes = [self::MODE_TEST, self::MODE_REVIEW];
        if (in_array($mode, $availableModes)) {
            $this->_mode = $mode;
        } else {
            $this->_mode = self::MODE_TEST;
        }
    }

    /**
     * @return bool
     */
    public function isReviewMode()
    {
        return $this->getMode() === self::MODE_REVIEW;
    }

    /**
     * Initialize class
     */
    public function __construct()
    {
        if (isset($_GET["course"]) && false != ($this->_activeCourse = $_GET["course"])) {
            $this->_currentStep = 2;
        }

        if (isset($_GET["mode"])) {
            $this->setMode($_GET["mode"]);
            if ($_GET["mode"] === self::MODE_REVIEW) {
                $this->_answer = [];
            }
        }

        if (isset($_SESSION['test']) && $this->_currentStep === 2) {
            foreach ($_SESSION['test'] as $field => $data) {
                $this->$field = $data;
            }

            if (isset($_POST['q'])) {
                $this->_currIndexQuestion = $_POST['q'];
                if ($this->_currIndexQuestion >= count($this->getCourseData("questions"))) {
                    $this->_currentStep = $this->isReviewMode() ? 1 : 3;
                }
            }

            if (isset($_POST['a'])) {
                $this->_answer = $_POST['a'];
                $this->_statistic();
            }

            return $this;
        }

        $this->_getAvailableCourses();
        if ($this->_currentStep === 2) {
            $this->_getCourseData();
            $_SESSION['test'] = $this;
        } else {
            unset($_SESSION['test']);
            unset($_SESSION['results']);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getActiveCourse()
    {
        return $this->_activeCourse;
    }

    /**
     * @return array
     */
    public function getAvailableCourses()
    {
        return $this->_availableCourses;
    }

    /**
     * @return mixed
     */
    public function getCountQuestion()
    {
        return $this->_countQuestion;
    }

    /**
     * @param null $param
     * @return array
     */
    public function getCourseData($param = null)
    {
        if (!is_null($param)) {
            return $this->_courseData[$param];
        }

        return $this->_courseData;
    }

    /**
     * @return array
     */
    public function getQuestion()
    {
        if (is_null($this->_activeCourse)) {
            return [];
        }

        return !isset($this->_order[$this->_currIndexQuestion]) || is_null($this->_order[$this->_currIndexQuestion])
            ? null
            : $this->_courseData['questions'][$this->_order[$this->_currIndexQuestion]];
    }

    /**
     * @return int
     */
    public function getCurrIndexQuestion()
    {
        return $this->_currIndexQuestion;
    }

    /**
     * @return bool
     */
    public function isRadioBtn()
    {
        $question = $this->getQuestion();
        $answerOptions = $question["answers"];
        $countValues = array_count_values(array_values($answerOptions));

        return $countValues["1"] === 1;
    }

    /**
     * @param string $index
     * @return string
     */
    public function getClass($index)
    {
        $question = $this->getQuestion();
        if (is_null($this->_answer)) {
            return "";
        }
        if (is_array($this->_answer) && !in_array($index, $this->_answer)) {
            if ($question['answers'][$index] == 1) {
                return $this->isReviewMode() ? self::ANSWER_CORRECT : self::ANSWER_MISSED;
            }

            return "";
        }
        if (is_string($this->_answer) && $index !== $this->_answer) {
            if ($question['answers'][$index] == 1) {
                return $this->isReviewMode() ? self::ANSWER_CORRECT : self::ANSWER_MISSED;
            }

            return "";
        }


        return $question['answers'][$index] == 1 ? self::ANSWER_CORRECT : self::ANSWER_WRONG;
    }

    /**
     * @param string $index
     * @return string
     */
    public function isChecked($index)
    {
        return ((is_array($this->_answer) && in_array($index, $this->_answer)) ||
            $index === $this->_answer) ? "checked" : "";
    }

    /**
     * @return bool
     */
    public function hasAnswer()
    {
        return isset($this->_answer);
    }

    /**
     * @return string
     */
    public function getCorrectPercents()
    {
        if (!isset($_SESSION["results"])) {
            $resFloat = 0;
        } else {
            $res = array_count_values(array_values($_SESSION["results"]));
            $resFloat = (float)(((isset($res["1"]) ? $res["1"] : 0) / count($_SESSION["results"])) * 100);
        }

        return number_format($resFloat, 2, '.', '');
    }

    /**
     * @return string
     */
    public function getCorrectCount()
    {
        $res = array_count_values(array_values($_SESSION["results"]));

        return isset($res["1"]) ? $res["1"] : 0;
    }


    /**
     * @return string
     */
    public function getWrongCount()
    {
        $res = array_count_values(array_values($_SESSION["results"]));

        return isset($res["0"]) ? $res["0"] : 0;
    }

    /**
     * @return string
     */
    public function getWrongPercents()
    {
        if (!isset($_SESSION["results"])) {
            $resFloat = 0;
        } else {
            $res = array_count_values(array_values($_SESSION["results"]));
            $resFloat = (float)(((isset($res["0"]) ? $res["0"] : 0) / count($_SESSION["results"])) * 100);
        }

        return number_format($resFloat, 2, '.', '');
    }

    /**
     * Reads directory with test files
     */
    protected function _getAvailableCourses()
    {
        if ($dirHandle = opendir(self::DIR)) {
            while (false != ($fileName = readdir($dirHandle))) {
                $testFile = self::DIR . $fileName;
                if (is_file($testFile) && is_readable($testFile)) {
                    $this->_availableCourses[$fileName] = [
                        "file" => $testFile,
                        "name" => $fileName,
                    ];
                }
            }
            closedir($dirHandle);
        }
    }

    /**
     *
     */
    protected function _getCourseData()
    {
        $info = pathinfo($this->_availableCourses[$this->_activeCourse]["file"]);
        if (in_array($info['extension'], ["yaml", "yml"])) {
            $this->_courseData = Spyc::YAMLLoad($this->_availableCourses[$this->_activeCourse]["file"]);
        } else if ($info['extension'] === "ini") {
            $this->_courseData = parse_ini_file($this->_availableCourses[$this->_activeCourse]["file"], true);
        } else if ($info['extension'] === "xml") {
            $this->_courseData = $this->_parseXml($this->_availableCourses[$this->_activeCourse]["file"]);
        }
        $this->_countQuestion = count($this->_courseData['questions']);

        $this->_shuffle($this->_courseData["questions"]);
    }

    /**
     * @param $array
     */
    protected function _shuffle(&$array)
    {
        for ($i = 0; $i < count($array); $i++) {
            $this->_order[] = $i;

            $keys = array_keys($array[$i]['answers']);

            shuffle($keys);
            $new = [];
            foreach ($keys as $key) {
                $new[$key] = $array[$i]['answers'][$key];
            }

            $array[$i]['answers'] = $new;
        }
        shuffle($this->_order);
    }

    /**
     *
     */
    protected function _statistic()
    {
        $question = $this->getQuestion();
        $res = [];
        if (is_array($this->_answer)) {
            $result = array_intersect($this->_answer, array_keys($question['answers']));
            foreach ($result as $answ) {
                if ($question['answers'][$answ] == 1) {
                    $res[] = 1;
                } else {
                    $res[] = 0;
                }
            }
        } else {
            if ($question['answers'][$this->_answer] == 1) {
                $res[] = 1;
            } else {
                $res[] = 0;
            }
        }

        $uniqueRes = array_unique($res);
        if (count($uniqueRes) === 1 && $uniqueRes[0] == 1) {
            $_SESSION["results"][$this->_order[$this->_currIndexQuestion]] = 1;
        } else {
            $_SESSION["results"][$this->_order[$this->_currIndexQuestion]] = 0;
        }
    }

    /**
     * @param $file
     * @return array
     */
    protected function _parseXml($file)
    {
        $data = [];
        $xml = simplexml_load_file($file, 'SimpleXMLElement', LIBXML_NOCDATA);
        $data['name'] = isset($xml['name']) ? (string)$xml['name'] : $this->_activeCourse;

        foreach ($xml->children() as $question) {
            $a = 1;

            $answers = [];
            foreach ($question->children() as $nodeName => $nodeContent) {
                if ("answer" !== $nodeName) {
                    continue;
                }
                if ($nodeContent->nodeType == XML_CDATA_SECTION_NODE) {
                    $answers[(string)$nodeContent->textContent] = (isset($nodeContent['correct']) && (string)$nodeContent['correct'] == "true") ? 1 : 0;
                } else {
                    $answers[(string)$nodeContent] = (isset($nodeContent['correct']) && (string)$nodeContent['correct'] == "true") ? 1 : 0;
                }
            }

            $valuesRes = array_count_values(array_values($answers));
            if (count($valuesRes) === 1 && isset($valuesRes[0])) {
                continue;
            }

            $data['questions'][] = [
                'text'    => isset($question->content)
                    ? $this->_innerXML($question->content)
                    : htmlspecialchars((string)$question['value']),
                'answers' => $answers
            ];
        }

        return $data;
    }

    /**
     * @param $content
     * @return string
     */
    private function _innerXML($content)
    {
        $innerXML = '';
        foreach (dom_import_simplexml($content)->childNodes as $child) {
            $innerXML .= $child->ownerDocument->saveXML($child);
        }

        return $innerXML;
    }
}