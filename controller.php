<?php
ob_start(); 
require_once('../classes/Constants.php');
require_once('../classes/BusinessManager.php');
require_once('../classes/MailHelper.php');
session_start();
$accion = $_REQUEST['accion'];

$mensajes = array();
$lst = array();
$bmgr = new BusinessManager(USER,PASS,HOST,DBNAME);

	if($accion == "grabar"){
		if($bmgr->validaSesion($_SESSION) == true){
			$bmgr->agregarInmueble($_REQUEST,$_FILES);	
			$inmuebles = $bmgr->getInmueblesPorNombre("");
				$_SESSION['inmuebles'] = $inmuebles;
				$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto(""); 
				  include("listadoInmuebles.php");
		}else{
			array_push($mensajes,MENSAJE_2);
			include('noTienePermisos.php');			
		}

	}else{
		if($accion == "editar"){
			if($bmgr->validaSesion($_SESSION) == true){
				$inmueble = $bmgr->getDetalleInmueble($_REQUEST['cveInmueble']);
				$_SESSION['inmueble'] = $inmueble;
				$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto(""); 
				  include("inmuebles.php");
			}else{
				array_push($mensajes,MENSAJE_2);
				include('noTienePermisos.php');
			}
			
		}else{
			if($accion == "actualizar"){
				if($bmgr->validaSesion($_SESSION) == true){
					$bmgr->actualizarInmueble($_REQUEST,$_FILES);
					
					$inmuebles = $bmgr->getInmueblesPorNombre("");
					$_SESSION['inmuebles'] = $inmuebles; 
					$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");
					  include("listadoInmuebles.php");
				}else{
					array_push($mensajes,MENSAJE_2);
					include('noTienePermisos.php');
				}
			}else{
				if($accion == "eliminar"){
					
					if($bmgr->validaSesion($_SESSION) == true){
						$bmgr->eliminarInmueble($_REQUEST['cveInmueble']);
						 $inmuebles = $bmgr->getInmueblesPorNombre($_REQUEST['txtNombre']);
						 $_SESSION['inmuebles'] = $inmuebles; 
						 $_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");
						 include("listadoInmuebles.php");
					}else{
						array_push($mensajes,MENSAJE_2);
						include('noTienePermisos.php');
					}
				}else{
					if($accion == "detalle"){
						$inmueble = $bmgr->getDetalleInmueble($_REQUEST['cveInmueble']);
						$_SESSION['inmueble'] = $inmueble; 
						$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");
						  include("detalleInmueble.php");
					}else{
						if($accion == "consulta"){
							if($bmgr->validaSesion($_SESSION) == true){
								$inmuebles = $bmgr->getInmueblesPorNombre($_REQUEST['txtNombre']);
								$_SESSION['inmuebles'] = $inmuebles;				
								$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");																
								  	include("listadoInmuebles.php");
							}else{
								array_push($mensajes,MENSAJE_2);
								include('noTienePermisos.php');
							}							 							  
						}else{
							if($accion == "alta"){
								if($bmgr->validaSesion($_SESSION) == true){
									$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");
							  		include("inmuebles.php");
								}else{
									array_push($mensajes,MENSAJE_2);
									include('noTienePermisos.php');
								}
							}else{
								if($accion == "cambiarEstadoMpo"){
									if($bmgr->validaSesion($_SESSION) == true){
										$inmueble = $bmgr->getInmuebleFromRequest($_REQUEST,$_FILES);										
										$_SESSION['inmueble'] = $inmueble;
										$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");										
									  	include("inmuebles.php");
									}else{
										array_push($mensajes,MENSAJE_2);
										include('noTienePermisos.php');
									}
									
								}else{
										if($accion == "consultaCliente"){										
										$inmueble = $bmgr->getInmuebleFromRequest($_REQUEST,$_FILES);									
										$_SESSION['inmueble'] = $inmueble;
										$inmuebles = $bmgr->getInmueblesPorZona($_REQUEST['cmbZonas'],
										$_REQUEST['cmbTiposInmueble'],$_REQUEST['cmbOperaciones']);
										
										if(count($inmuebles) == 0){
										array_push($mensajes,MENSAJE_1);
										$_SESSION['mensajes'] = $mensajes;	
										}
									
										
										$_SESSION['inmuebles'] = $inmuebles;
										$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");
										include("listadoInmueblesCliente.php");
									
										}else{
											if($accion == "cambiarEstadoMpoCte"){
												$inmueble = $bmgr->getInmuebleFromRequest($_REQUEST,$_FILES);	
																
												$_SESSION['inmueble'] = $inmueble;					
												$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");					
											include("listadoInmueblesCliente.php");										
											}else{
												if($accion == "consultaCte"){
													$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");																				
													include("listadoInmueblesCliente.php");							 							  
												}else{
													if($accion == "mail"){																				
													$inmueble = $bmgr->getDetalleInmueble($_REQUEST['cveInmueble']);
													$_SESSION['inmueble'] = $inmueble;
													$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");
													include("mail.php");							 							  
													}else{
														if($accion == "sendMail"){
														$inmueble = $bmgr->getDetalleInmueble($_REQUEST['cveInmueble']);																				
														$mail = new MailHelper($inmueble,$_REQUEST);			
														$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");																																				
														include("graciasContactarnos.php");							 							  
														}else{
															if($accion == "grabarUsuario"){
																if($bmgr->validaSesion($_SESSION) == true){
																	$bmgr->agregarUsuario($_REQUEST);	
																	$usuarios = $bmgr->getUsuariosPorLogin("");
																		$_SESSION['usuarios'] = $usuarios; 
																		$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");
																		  include("listadoUsuarios.php");
																}else{
																		array_push($mensajes,MENSAJE_2);
																		include('noTienePermisos.php');
																}
														
															}else{
															
																if($accion == "editarUsuario"){
																	if($bmgr->validaSesion($_SESSION) == true){
																		$usuario = $bmgr->getDetalleUsuario($_REQUEST['cveUsuario']);
																		$_SESSION['usuario'] = $usuario; 
																		$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");
																		  include("altaUsuarios.php");
																	}else{
																		array_push($mensajes,MENSAJE_2);
																		include('noTienePermisos.php');
																	}
																}else{
																	
																	if($accion == "actualizarUsuario"){
																		if($bmgr->validaSesion($_SESSION) == true){	
																			$bmgr->actualizarUsuario($_REQUEST);
																			
																			$usuarios = $bmgr->getUsuariosPorLogin("");
																			$_SESSION['usuarios'] = $usuarios; 
																			$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");
																			  include("listadoUsuarios.php");
																		}else{
																			array_push($mensajes,MENSAJE_2);
																			include('noTienePermisos.php');
																		}
																	}else{
																	
																	if($accion == "eliminarUsuario"){
																		
																		if($bmgr->validaSesion($_SESSION) == true){
																			$bmgr->eliminarUsuario($_REQUEST['cveUsuario']);
																			  $usuarios = $bmgr->getUsuariosPorLogin($_REQUEST['txtLogin']);
																			$_SESSION['usuarios'] = $usuarios; 
																			$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");
																			  include("listadoUsuarios.php");
																		}else{
																			array_push($mensajes,MENSAJE_2);
																			include('noTienePermisos.php');
																		}
																		}else{
																			if($accion == "consultaUsuarios"){
																				
																				if($bmgr->validaSesion($_SESSION) == true){
																					$usuarios = $bmgr->getUsuariosPorLogin($_REQUEST['txtLogin']);
																					$_SESSION['usuarios'] = $usuarios;					
																					$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");															
																					  	include("listadoUsuarios.php");							 							  
																				}else{
																					array_push($mensajes,MENSAJE_2);
																					include('noTienePermisos.php');
																				}
																			}else{
																				if($accion == "altaUsuarios"){
																					if($bmgr->validaSesion($_SESSION) == true){
																						$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");
																				  		include("altaUsuarios.php");
																					}else{
																						array_push($mensajes,MENSAJE_2);
																						include('noTienePermisos.php');
																					}
																				}else{
																					if($accion == "login"){
																						$usr = new Usuario();
																						if($bmgr->validaUsuario($_REQUEST) == true){
																							$usr->login = $_REQUEST['txtLogin'];
																							$_SESSION['admin'] = $usr;
																							$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");
																							include('listadoInmuebles.php');
																						}else{
																							array_push($mensajes,MENSAJE_2);
																							include('noTienePermisos.php');
																						}
																						
																					  
																					}else{
																					
																							if($accion == "logout"){																																													
																									$_SESSION['admin'] = NULL;							
																									$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");																	
																							  include("quienes_somos.php");
																							}else{
																								if($accion == "grabarNoticia"){
																									if($bmgr->validaSesion($_SESSION) == true){
																										$bmgr->agregarNoticia($_REQUEST);	
																										$noticias = $bmgr->getNoticiasPorTexto("");																																				
																											$_SESSION['noticias'] = $noticias; 
																											$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");
																											  include("listadoNoticias.php");
																									}else{
																											array_push($mensajes,MENSAJE_2);
																											include('noTienePermisos.php');
																									}
																							
																								}else{
																									
																									if($accion == "editarNoticia"){
																										if($bmgr->validaSesion($_SESSION) == true){
																											$noticia = $bmgr->getDetalleNoticia($_REQUEST['cveNoticia']);
																											$_SESSION['noticia'] = $noticia; 
																											$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");
																											  include("altaNoticias.php");
																										}else{
																											array_push($mensajes,MENSAJE_2);
																											include('noTienePermisos.php');
																										}
																									}else{
																										if($accion == "actualizarNoticia"){
																											if($bmgr->validaSesion($_SESSION) == true){	
																												$bmgr->actualizarNoticia($_REQUEST);
																												
																												$noticias = $bmgr->getNoticiasPorTexto("");
																												$_SESSION['noticias'] = $noticias; 
																												$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");
																												  include("listadoNoticias.php");
																											}else{
																												array_push($mensajes,MENSAJE_2);
																												include('noTienePermisos.php');
																											}
																										}else{
																											if($accion == "eliminarNoticia"){
																		
																												if($bmgr->validaSesion($_SESSION) == true){
																													$bmgr->eliminarNoticia($_REQUEST['cveNoticia']);
																													  $noticias = $bmgr->getNoticiasPorTexto($_REQUEST['txtNoticia']);
																													$_SESSION['noticias'] = $noticias; 
																													$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");
																													  include("listadoNoticias.php");
																												}else{
																													array_push($mensajes,MENSAJE_2);
																													include('noTienePermisos.php');
																												}
																											}else{
																												if($accion == "consultaNoticias"){
																				
																													if($bmgr->validaSesion($_SESSION) == true){
																														$noticias = $bmgr->getNoticiasPorTexto($_REQUEST['txtNoticia']);
																														
																														$_SESSION['noticias'] = $noticias;						
																														$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");														
																														  	include("listadoNoticias.php");							 							  
																													}else{
																														array_push($mensajes,MENSAJE_2);
																														include('noTienePermisos.php');
																													}
																												}else{
																													if($accion == "altaNoticias"){
																															if($bmgr->validaSesion($_SESSION) == true){
																																$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");
																														  		include("altaNoticias.php");
																															}else{
																																array_push($mensajes,MENSAJE_2);
																																include('noTienePermisos.php');
																															}
																													}else{																														
																														if($accion == "imprimir"){																															
																																$inmueble = $bmgr->getDetalleInmueble($_REQUEST['cveInmueble']);
																																$_SESSION['inmueble'] = $inmueble; 																															
																																include("imprimir.php");																													
																														}else{																															
																															if($accion == "quienes_somos"){																																																															
																																$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");																														
																																include("quienes_somos.php");																													
																															}else{
																																if($accion == "servicios"){																																																															
																																$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");																														
																																include("servicios.php");																													
																																}else{
																																	if($accion == "mail2"){																				
																																		$inmueble = $bmgr->getDetalleInmueble($_REQUEST['cveInmueble']);
																																		$_SESSION['inmueble'] = $inmueble;
																																		$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");
																																		include("mail2.php");							 							  
																																		}else{
																																			if($accion == "sendMail2"){
																																			$inmueble = $bmgr->getDetalleInmueble($_REQUEST['cveInmueble']);																				
																																			$mail = new MailHelper($inmueble,$_REQUEST);			
																																			$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");																																				
																																			include("graciasRecomendar.php");							 							  
																																			}else{	
																																				if($accion == "favoritos"){
																																				$inmueble = $bmgr->getDetalleInmueble($_REQUEST['cveInmueble']);																				
																																				$_SESSION['inmueble'] = $inmueble;			
																																				$_SESSION['noticiasView'] = $bmgr->getNoticiasPorTexto("");
																																				
																																				if(isset($_SESSION['favoritos'])){
																																				$lst = $_SESSION['favoritos'];																																																																								
																																				}
																																																																						
																																				if(!array_key_exists($inmueble->cveInmueble, $lst)){
																																				$lst[$inmueble->cveInmueble] = $inmueble;
																																				}
																																				
																																				
																																				$_SESSION['favoritos'] = $lst;
																																				include("detalleInmueble.php");							 							  
																																				}else{	
																																					array_push($mensajes,MENSAJE_2);
																																					include('noTienePermisos.php');
																																				}
																																			}
																																		}
																																	}
																															}
																														}
																													}
																												}
																												
																											}
																										}
																										
																										
																									}
																								}	
																							}	
																					}
																					
																				}
																			}	
																		}	
																	}
																}	
															}
														}	
														
													}
													
												}
											}
											
										}
									
									}
							}
						}		
					}
				}
			}
		}
	}
?>
