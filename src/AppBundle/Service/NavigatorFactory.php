<?php


namespace AppBundle\Service;


use Symfony\Component\HttpFoundation\RequestStack;

class NavigatorFactory
{

    /**
     * @var\Doctrine\Bundle\DoctrineBundle\Registry\object
     */
    protected $doctrine;

    /**
     * @var string
     */
    protected $page;

    /**
     * @var array
     */
    protected $filter;

    /**
     * @var string
     */
    protected $context;

    /**
     * NavigatorFactory constructor.
     * @param RequestStack $requestStack
     * @param $doctrine
     */
    public function __construct($requestStack, $doctrine)
    {
        $request        = $requestStack->getCurrentRequest();
        $controller     = $request->get('_controller');
        $this->page     = $request->get("page", 1);
        $this->filter   = $request->get("app_bundle_filter_type");
        if (is_null($this->filter)) {
            $this->filter = $this->transform($request->get("filter"));
        } else {
            // reset page when submit
            $this->page = 1;
        }
        $controller     = explode('::', $controller);
        $controller     = explode('\\', $controller[0]);

        $this->context  = preg_replace('/Controller/', '', $controller[count($controller) - 1]);
        $this->doctrine = $doctrine;
    }

    /**
     * @return Navigator
     */
    public function get()
    {
        $repositoryName = "AppBundle:" . $this->context;
        $repository     = $this->doctrine->getRepository($repositoryName);
        return new Navigator($this->context, $repository, $this->page,$this->filter);
    }

    private function transform($data){
        $dataArray = [];
        if (is_string($data)) {
            if ($data !== "") {
                $data = explode("&", $data);
                foreach ($data as $row) {
                    $field = explode("=", $row);
                    $dataArray[$field[0]] = $field[1];
                }
            }
        }
        return $dataArray;
    }
}


