<?php
namespace Behat\Zf2Extension\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface,
    Symfony\Component\DependencyInjection\ContainerBuilder;



class Zf2ApplicationCompilerPasses  implements CompilerPassInterface{
  
    public function process(ContainerBuilder $container) {
         
      //Loading Configuration
      $applicationPath = $container->getParameter('behat.paths.base');
      $configurationPath = $container->getParameter("behat.zf2_extension.application_config_path");
      $fullApplicationConfigPath = $applicationPath.DIRECTORY_SEPARATOR.$configurationPath;
                                            
      if(file_exists($fullApplicationConfigPath)){
         
           $configuration = require $fullApplicationConfigPath;
           
      }
            
      $container->setParameter('behat.zf2_extension.application_config_path',$configuration);
      
      //Get A list of all laoded module
      
      $mvcApplication = $container->get("behat_zf2_extension.application");
      
     if($moduleName = $container->getParameter('behat.zf2_extension.module')) {
      
         $loadedModules = $mvcApplication->getServiceManager()->get('ModuleManager')->getLoadedModules();
      
         $moduleConfig = $loadedModules[$moduleName]->getAutoloaderConfig();
         $modulePath = $moduleConfig['Zend\Loader\StandardAutoloader']['namespaces'][$moduleName];
         
         $container->set("behat.paths.features",
                      $modulePath.DIRECTORY_SEPARATOR.$container->getParameter("behat.zf2_extension.context.path_suffix")
          );
                 
      }
      
      
    }    
    
    
    
}

?>