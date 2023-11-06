<?php
                    if(empty($messages)) {
                        include '../layouts/message/empty-message.php';
                    } else {
                        $date = date('Y-m-d');
                        foreach ($messages as $k => $message) {
                            if(!$message->isEmpty()) {
                                $trs = false;

                                // if(strtotime((new \DateTime($message->getDat()))->format('Y-m-d')) == strtotime($date) && $k == 0) {
                                //     $date = (new \DateTime($message->getDat()))->format('Y-m-d');
                                //     if(strtotime($date) == strtotime(date('Y-m-d'))) {
                                //         $trs = true;
                                //         $date = 'aujourd\'hui';
                                //     }
                                //     include('layouts/message/_date.php');
                                //     if($trs) {
                                //         $date = (new \DateTime($message->getDat()))->format('Y-m-d');
                                //     }
                                // } elseif(strtotime((new \DateTime($message->getDat()))->format('Y-m-d')) !== strtotime($date)) {
                                //     $date = (new \DateTime($message->getDat()))->format('Y-m-d');
                                //     if(strtotime($date) == strtotime(date('Y-m-d'))) {
                                //         $trs = true;
                                //         $date = 'aujourd\'hui';
                                //     }
                                //     include('_date.php');
                                //     if($trs) {
                                //         $date = (new \DateTime($message->getDat()))->format('Y-m-d');
                                //     }
                                // }

                                if(strtotime((new \DateTime($message->getDat()))->format('Y-m-d')) != strtotime($date)) {
                                    $date = (new \DateTime($message->getDat()))->format('Y-m-d');
                                    if(strtotime($date) == strtotime(date('Y-m-d'))) {
                                        $trs = true;
                                        $date = 'aujourd\'hui';
                                    }
                                    include('_date.php');
                                    if($trs) {
                                        $date = (new \DateTime($message->getDat()))->format('Y-m-d');
                                    }
                                }

                                if($message->getExpediteur() === $account) {
                                    include('_message-right.php');
                                } else {
                                    include('_message-left.php');
                                }
                            }
                        }
                    }