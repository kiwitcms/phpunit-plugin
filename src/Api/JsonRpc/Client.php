<?php

namespace KiwiTcmsPhpUnitPlugin\Api\JsonRpc;

use Graze\GuzzleHttp\JsonRpc\Client as GuzzleJsonRpcClient;
use KiwiTcmsPhpUnitPlugin\Api\ClientInterface;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\ClassificationRepository;
use KiwiTcmsPhpUnitPlugin\Api\Model\Classification;
use KiwiTcmsPhpUnitPlugin\Config\ConfigInterface;
use KiwiTcmsPhpUnitPlugin\Api\ClientException;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\ProductRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\ProductVersionRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\TestPlanRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\TestRunRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\BuildRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\TestCaseRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\CategoryRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\TestExecutionRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\UserRepository;
use KiwiTcmsPhpUnitPlugin\Api\Model\ProductVersion;
use KiwiTcmsPhpUnitPlugin\Api\Model\TestPlan;
use KiwiTcmsPhpUnitPlugin\Api\Model\TestRun;
use KiwiTcmsPhpUnitPlugin\Api\Model\Build;
use KiwiTcmsPhpUnitPlugin\Api\Model\TestExecution;
use KiwiTcmsPhpUnitPlugin\Api\Model\TestCase;
use KiwiTcmsPhpUnitPlugin\Api\Model\Product;
use KiwiTcmsPhpUnitPlugin\Api\Model\User;
use KiwiTcmsPhpUnitPlugin\Api\Model\Category;

class Client implements ClientInterface
{

    /**
     * @var GuzzleJsonRpcClient
     */
    private $client;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var ProductVersionRepository
     */
    private $productVersionRepository;

    /**
     * @var TestPlanRepository
     */
    private $testPlanRepository;

    /**
     * @var TestRunRepository
     */
    private $testRunRepository;

    /**
     * @var BuildRepository
     */
    private $buildRepository;

    /**
     * @var TestCaseRepository
     */
    private $testCaseRepository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var TestExecutionRepository
     */
    private $testExecutionRepository;

    /**
     * @var ClassificationRepository
     */
    private $classificationRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var array
     */
    private $testResults;

    /**
     * @var TestRun
     */
    private $testRun;

    /**
     * @var Product
     */
    private $product;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Category
     */
    private $category;

    /**
     * @var ProductVersion
     */
    private $version;

    /**
     * @var Classification
     */
    private $classification;

    private $priorityId;

    private $caseStatusId;

    public function __construct(
        GuzzleJsonRpcClient $client,
        ConfigInterface $config,
        ProductRepository $productRepository,
        ProductVersionRepository $productVersionRepository,
        TestPlanRepository $testPlanRepository,
        TestRunRepository $testRunRepository,
        BuildRepository $buildRepository,
        TestCaseRepository $testCaseRepository,
        CategoryRepository $categoryRepository,
        TestExecutionRepository $testExecutionRepository,
        UserRepository $userRepository,
        ClassificationRepository $classificationRepository
    ) {
        $this->client = $client;
        $this->config = $config;

        $this->productRepository = $productRepository;
        $this->productVersionRepository = $productVersionRepository;
        $this->testPlanRepository = $testPlanRepository;
        $this->testRunRepository = $testRunRepository;
        $this->buildRepository = $buildRepository;
        $this->testCaseRepository = $testCaseRepository;
        $this->categoryRepository = $categoryRepository;
        $this->testExecutionRepository = $testExecutionRepository;
        $this->userRepository = $userRepository;
        $this->classificationRepository = $classificationRepository;

        $this->testResults = [];
    }

    private function logout(): void
    {
        $this->client->send($this->client->request(123, 'Auth.logout'));
    }

    public function init(): void
    {
        $this->user = $this->getUserOrFail();

        if (!empty($this->config->getTestRunId())) {
            $testRun = $this->testRunRepository->findById($this->config->getTestRunId());

            if (empty($testRun)) {
                throw new ClientException(sprintf("Test run with id %d not found!", $this->config->getTestRunId()));
            }

            $this->classification = $this->getClassification();

            if (!empty($this->classification)) {
                throw new ClientException(sprintf("Missing classification"));
            }

            $this->testRun = $testRun;

            $testPlan = $this->testPlanRepository->findById($this->testRun->getPlanId());

            $product = $this->productRepository->findById($testPlan->getProductId());

            if (!empty($product)) {
                $this->product = $product;
                $this->category = $this->getFirstProductCategoryOrFail();
            }
        } else {
            $this->classification = $this->getClassification();
            $this->product = $this->getProductOrCreate();
            $this->version = $this->getProductVersionOrCreate();
            $this->category = $this->getFirstProductCategoryOrFail();
            $this->testRun = $this->createTestRun();
        }

        $this->priorityId = $this->getPriorityId();
        $this->caseStatusId = $this->getConfirmedCaseStatusId();
    }

    public function finish(): void
    {
        $this->addTestResultsToTestRun();
        $this->logout();
    }

    public function getUserOrFail(): User
    {
        $user = $this->userRepository->findByUsername($this->config->getUsername());

        if (empty($user)) {
            throw new ClientException("User not found!");
        }

        return $user;
    }

    public function getClassification(): Classification
    {
        $classification = $this->classificationRepository->findFirst();
        if (empty($classification)) {
            throw new ClientException(sprintf("Missing classification"));
        }

        return $classification;
    }

    public function getPriorityId()
    {
        $response = $this->client->send($this->client->request(123, 'Priority.filter', [['id__gt' => 0]]));
        $result = $response->getRpcResult();
        if (empty($result)) {
            throw new ClientException(sprintf("Missing Priority"));
        }

        return $result[0]['id'];
    }

    public function getConfirmedCaseStatusId()
    {
        $response = $this->client->send(
            $this->client->request(123, 'TestCaseStatus.filter', [['name' => 'CONFIRMED']])
        );

        $result = $response->getRpcResult();
        if (empty($result)) {
            throw new ClientException(sprintf("Missing TestCaseStatus"));
        }

        return $result[0]['id'];
    }

    public function getProductOrCreate(): Product
    {
        $product = $this->productRepository->findByName($this->config->getProductName());

        if (empty($product)) {
            return $this->createProduct();
        }

        return $product;
    }

    public function createProduct(): Product
    {
        $product = new Product();
        $product->setName($this->config->getProductName());
        $product->setClassification($this->classification->getId());
        $product->setDescription("");

        return $this->productRepository->create($product);
    }

    public function getFirstProductCategoryOrFail(): Category
    {
        $category = $this->categoryRepository->findFirstByProductId($this->product->getId());

        if (empty($category)) {
            throw new ClientException("No product category found!");
        }

        return $category;
    }

    public function getProductVersionOrCreate(): ProductVersion
    {
        $productVersion = $this->productVersionRepository->findByProductIdAndVersion(
            $this->product->getId(),
            $this->config->getProductVersion()
        );

        if (empty($productVersion)) {
            return $this->createtProductVersion();
        }

        return $productVersion;
    }

    public function createtProductVersion(): ProductVersion
    {
        $productVersion = new ProductVersion();
        $productVersion->setProductId($this->product->getId());
        $productVersion->setValue($this->config->getProductVersion());

        return $this->productVersionRepository->create($productVersion);
    }

    public function createTestPlan(): TestPlan
    {
        $productVersion = $this->getProductVersionOrCreate();

        $testPlan = new TestPlan();
        $testPlan->setProductId($this->product->getId());
        $testPlan->setTypeId(1);
        $testPlan->setProductVersionId($productVersion->getId());
        $testPlan->setText("WIP");

        $testPlanName = sprintf('Automated test plan for %s', $this->product->getName());
        $testPlan->setName($testPlanName);

        return $this->testPlanRepository->create($testPlan);
    }

    public function createTestRun(): TestRun
    {
        $build = $this->getBuildOrCreate();
        $testPlan = $this->createTestPlan();

        $testRun = new TestRun();
        $testRun->setBuildId($build->getBuildId());
        $testRun->setPlanId($testPlan->getPlanId());
        $testRun->setManagerId($this->user->getId());
        $testRun->setSummary('Automated test run ' . date('Y-m-d H:i:s'));

        return $this->testRunRepository->create($testRun);
    }

    public function getBuildOrCreate(): Build
    {
        $build = $this->buildRepository->findByProductIdAndBuild(
            $this->version->getId(),
            $this->config->getBuild()
        );

        if (empty($build)) {
            return $this->createBuild();
        }

        return $build;
    }

    public function createBuild(): Build
    {
        $build = new Build();
        $build->setVersion($this->version->getId());
        $build->setName($this->config->getBuild());

        return $this->buildRepository->create($build);
    }

    public function addTestResult($name, $result, $containingClass, $text = "", $executionTime = null)
    {
        $summary = $containingClass . '::' . $name;
        $testResultId = md5($containingClass . '::' . $name);
        if (!isset($this->testResults[$testResultId])) {
            $testCase = new TestCase();

            $testCase->setCategoryId($this->category->getId());
            $testCase->setProductId($this->product->getId());
            $testCase->setSummary($summary);
            $testCase->setCaseStatusId($this->caseStatusId);
            $testCase->setIsAutomated(true);
            $testCase->setPriorityId($this->priorityId);

            $this->testResults[$testResultId] = [
                'testCase' => $testCase,
                'testExecutionStatusId' => $this->getCaseRunStatusIdByResult($result),
                'testExecutionTime' => $executionTime,
                'testExecutionText' => $text,
            ];
        }
    }

    public function createTestExecution(int $testCaseId, int $statusId): TestExecution
    {
        $testExecution = new TestExecution();
        $testExecution->setRunId($this->testRun->getRunId());
        $testExecution->setCaseId($testCaseId);
        $testExecution->setBuildId($this->testRun->getBuildId());

        $testExecution = $this->testExecutionRepository->create($testExecution);

        return $this->testExecutionRepository->updateStatus(
            $testExecution->getId(),
            $statusId
        );
    }

    private function updateOrCreateTestExecution(TestCase $existingTestCase, int $statusId): TestExecution
    {
        $testExecution = $this->testExecutionRepository->findByTestCaseIdAndTestRunId(
            $existingTestCase->getCaseId(),
            $this->testRun->getRunId()
        );

        if ($testExecution) {
            $this->testExecutionRepository->updateStatus($testExecution->getId(), $statusId);
        } else {
            $testExecution = $this->createTestExecution($existingTestCase->getCaseId(), $statusId);
        }

        return $testExecution;
    }

    private function createTestCaseAndTestExecution(TestCase $newTestCase, int $statusId): TestExecution
    {
        $testCase = $this->testCaseRepository->createModel($newTestCase);

        $this->testPlanRepository->addTestCase($this->testRun->getPlanId(), $testCase->getCaseId());

        $testExecution = $this->createTestExecution($testCase->getCaseId(), $statusId);

        return $testExecution;
    }

    private function createTextExecutionComment(
        TestExecution $testExecution,
        string $executionText,
        float $executionTime
    ) {
        $executionTimeStr = sprintf("Execution time: %fs\n\n", $executionTime);
        $executionText = $executionTimeStr . $executionText;
        $this->testExecutionRepository->addComment($testExecution->getId(), $executionText);
    }

    private function addTestResultsToTestRun()
    {
        if (empty($this->testRun)) {
            return;
        }

        /** TestCase[] $existingTestCases */
        $existingTestCases = $this->testCaseRepository->findByTestRunId($this->testRun->getRunId());

        foreach ($this->testResults as $testResultId => $testResult) {
            $testExecution = null;
            if (isset($existingTestCases[$testResultId])) {
                $testExecution = $this->updateOrCreateTestExecution(
                    $existingTestCases[$testResultId],
                    $testResult['testExecutionStatusId']
                );
            } else {
                $testExecution = $this->createTestCaseAndTestExecution(
                    $testResult['testCase'],
                    $testResult['testExecutionStatusId']
                );
            }

            $this->createTextExecutionComment(
                $testExecution,
                $testResult['testExecutionText'],
                $testResult['testExecutionTime']
            );
        }
    }

    private function getCaseRunStatusIdByResult(string $result): int
    {
        switch ($result) {
            case "PASS":
                return 4;

            case "FAIL":
            case "WARNING":
                return 5;

            case "ERROR":
                return 7;

            case "INCOMPLETE":
            case "RISKY":
            case "SKIPPED":
                return 8;
        }

        return 7;
    }
}
