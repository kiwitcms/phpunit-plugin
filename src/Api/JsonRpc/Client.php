<?php

namespace KiwiTcmsPhpUnitPlugin\Api\JsonRpc;

use Graze\GuzzleHttp\JsonRpc\Client as GuzzleJsonRpcClient;
use KiwiTcmsPhpUnitPlugin\Api\ClientInterface;
use KiwiTcmsPhpUnitPlugin\Config\ConfigInterface;
use KiwiTcmsPhpUnitPlugin\Api\ClientException;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\ProductRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\ProductVersionRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\TestPlanRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\TestRunRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\BuildRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\TestCaseRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\CategoryRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\TestCaseRunRepository;
use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\UserRepository;
use KiwiTcmsPhpUnitPlugin\Api\Model\ProductVersion;
use KiwiTcmsPhpUnitPlugin\Api\Model\TestPlan;
use KiwiTcmsPhpUnitPlugin\Api\Model\TestRun;
use KiwiTcmsPhpUnitPlugin\Api\Model\Build;
use KiwiTcmsPhpUnitPlugin\Api\Model\TestCaseRun;
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
     * @var TestCaseRunRepository
     */
    private $testCaseRunRepository;

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
        TestCaseRunRepository $testCaseRunRepository,
        UserRepository $userRepository
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
        $this->testCaseRunRepository = $testCaseRunRepository;
        $this->userRepository = $userRepository;

        $this->testResults = [];
    }

    private function logout(): void
    {
        $this->client->send($this->client->request(123, 'Auth.logout'));
    }

    public function init(): void
    {
        $this->product = $this->getProductOrFail();
        $this->category = $this->getFirstProductCategoryOrFail();
        $this->user = $this->getUserOrFail();
        $this->testRun = $this->getTestRunOrCreate();
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

    public function getProductOrFail(): Product
    {
        $product = $this->productRepository->findByName($this->config->getProductName());

        if (empty($product)) {
            throw new ClientException("Product not found!");
        }

        return $product;
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
        $testPlan->setName('Auto Test Plan ' . date('Y-m-d H:i:s'));

        return $this->testPlanRepository->create($testPlan);
    }

    public function getTestRunOrCreate(): TestRun
    {
        $testRun = $this->testRunRepository->findById($this->config->getTestRunId());

        if (empty($testRun)) {
            return $this->createTestRun();
        }

        return $testRun;
    }

    public function createTestRun(): TestRun
    {
        $build = $this->getBuildOrCreate();
        $testPlan = $this->createTestPlan();

        $testRun = new TestRun();
        $testRun->setBuildId($build->getBuildId());
        $testRun->setPlanId($testPlan->getPlanId());
        $testRun->setManagerId($this->user->getId());
        $testRun->setSummary('Auto Test Run ' . date('Y-m-d H:i:s'));

        return $this->testRunRepository->create($testRun);
    }

    public function getBuildOrCreate(): Build
    {
        $build = $this->buildRepository->findByProductIdAndBuild(
            $this->product->getId(),
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
        $build->setProductId($this->product->getId());
        $build->setName($this->config->getBuild());

        return $this->buildRepository->create($build);
    }

    public function addTestResult($name, $result, $containingClass, $exception = "")
    {
        $summary = $containingClass . '::' . $name;
        $testResultId = md5($containingClass . '::' . $name);
        if (!isset($this->testResults[$testResultId])) {
            $testCase = new TestCase();
            $testCase->setCategoryId($this->category->getId());
            $testCase->setProductId($this->product->getId());
            $testCase->setSummary($summary);
            $testCase->setCaseStatusId(2);
            $testCase->setIsAutomated(true);
            $testCase->setPriorityId(1);
            $testCase->setAuthorId(1);

            $this->testResults[$testResultId] = [
                'testCase' => $testCase,
                'testCaseRunStatus' => $this->getCaseRunStatusIdByResult($result)
            ];
        }
    }

    public function createTestCaseRun(int $testCaseId, int $statusId): TestCaseRun
    {
        $testCaseRun = new TestCaseRun();
        $testCaseRun->setRunId($this->testRun->getRunId());
        $testCaseRun->setCaseId($testCaseId);
        $testCaseRun->setBuildId($this->testRun->getBuildId());

        $testCaseRun = $this->testCaseRunRepository->create($testCaseRun);

        return $this->testCaseRunRepository->updateStatus(
            $testCaseRun->getCaseRunId(),
            $statusId
        );
    }

    private function updateOrCreateTestCaseRun(TestCase $existingTestCase, int $statusId)
    {
        $testCaseRun = $this->testCaseRunRepository->findByTestCaseIdAndTestRunId(
            $existingTestCase->getCaseId(),
            $this->testRun->getRunId()
        );

        if ($testCaseRun) {
            $this->testCaseRunRepository->updateStatus($testCaseRun->getCaseRunId(), $statusId);
        } else {
            $testCaseRun = $this->createTestCaseRun($existingTestCase->getCaseId(), $statusId);
        }
    }

    private function createTestCaseAndTestCaseRun(TestCase $newTestCase, int $statusId)
    {
        $testCase = $this->testCaseRepository->createModel($newTestCase);

        $this->testPlanRepository->addTestCase($this->testRun->getPlanId(), $testCase->getCaseId());

        $this->createTestCaseRun($testCase->getCaseId(), $statusId);
    }

    private function addTestResultsToTestRun()
    {
        /** TestCase[] $existingTestCases */
        $existingTestCases = $this->testCaseRepository->findByTestRunId($this->testRun->getRunId());

        foreach ($this->testResults as $testResultId => $testResult) {
            if (isset($existingTestCases[$testResultId])) {
                $this->updateOrCreateTestCaseRun($existingTestCases[$testResultId], $testResult['testCaseRunStatus']);
            } else {
                $this->createTestCaseAndTestCaseRun($testResult['testCase'], $testResult['testCaseRunStatus']);
            }
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
