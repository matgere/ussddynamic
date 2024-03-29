<?php
namespace Racine;
//require_once '../lib/doctrine/vendor/autoload.php';
if (is_file('lib/doctrine/vendor/autoload.php'))
{
    require_once 'lib/doctrine/vendor/autoload.php';
}else if (is_file('../lib/doctrine/vendor/autoload.php')){
    require_once '../lib/doctrine/vendor/autoload.php';
}else if (is_file('../../lib/doctrine/vendor/autoload.php')){
   require_once '../../lib/doctrine/vendor/autoload.php';
}else if (is_file('../../../lib/doctrine/vendor/autoload.php')){
    require_once '../../../lib/doctrine/vendor/autoload.php';
}else if (is_file('../../../../lib/doctrine/vendor/autoload.php')){
    require_once '../../../../lib/doctrine/vendor/autoload.php';
}
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Common\DoctrineLogger as DoctrineLogger;
class Bootstrap{
    public static $entityManager;
    public function __construct() {
    }
    public  function getEntityManager() {
        $paths = array("../backend/src/be/");
        $isDevMode = true;
        $parameters = parse_ini_file("parameters.ini");
        // the connection configuration
        $dbParams = array(
            'driver'   => 'pdo_mysql',
            'host'     => $parameters['host'],
            'dbname'   => $parameters['dbname'],
            'user'     => $parameters['user'],
            'password' => $parameters['password'],
            'port'     => $parameters['port'],
            'charset' => 'utf8',
            'driverOptions' => array(
                1002=>'SET NAMES utf8'
            )
        );
        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        //$config->setProxyDir('lib');
        //$config->setProxyNamespace('DoctrineORM\Proxies');
        $config->setSQLLogger(new DoctrineLogger());
        $config->addCustomStringFunction('DATE_FORMAT', 'UVd\DoctrineFunction\DateFormat');
        Bootstrap::$entityManager = EntityManager::create($dbParams, $config);
        
        return Bootstrap::$entityManager;
    }

    public function setEntityManager($entityManager) {
        $this->entityManager = $entityManager;
    }
}
$bootstrap=new Bootstrap();
$bootstrap->getEntityManager();
