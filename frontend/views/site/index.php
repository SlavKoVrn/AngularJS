<?php

use common\assets\AngularAsset;
AngularAsset::register($this);

/* @var $this yii\web\View */

$this->title = 'My Yii Application';

$css =<<<CSS
    .modal-content .modal-header .close {
        position: absolute;
        width: 32px;
        height: 32px;
        right: -14px;
        top: -14px;
        opacity: 1;
        border-radius: 50%;
        background-color: #fff;
        -webkit-box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.4);
        box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.4);
        outline: none;
        margin: 0;
        padding: 0; }
    .modal-content .modal-header .close span {
        color: #a8a8a8;
        font-weight: normal;
        text-shadow: none;
        margin-right: -2px; }
    .modal-content .modal-header .close:hover {
        opacity: 1; }
    @media screen and (max-width: 576px) {
        .modal-content .modal-header .close {
            -webkit-box-shadow: none;
            box-shadow: none;
            right: 5px;
            top: 5px; }
        .modal-content .modal-header .close span {
            font-size: 35px; }
    }
CSS;
$this->registerCss($css);
?>
<div class="site-index">

    <div class="users-content">
        <div class="row">
            <div class="col-lg-12">

                <div class="users-app-controller"
                     data-ng-app="app"
                     data-ng-controller="UsersController">

                    <div class="alert alert-{{alert.type}} alert-dismissible" ng-show="showAlert == true">
                        <button type="button" class="close"  aria-label="Close" ng-click="closeAlert()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{alert.msg}}
                    </div>

                    <div>
                        <div data-ng-show="users.length > 0">
                            <h2>Users</h2>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <th>Id</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Registration IP</th>
                                    <th>Last login at</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody>
                                <tr data-ng-repeat="user in users">
                                    <td>{{user.id}}</td>
                                    <td>{{user.username}}</td>
                                    <td>{{user.email}}</td>
                                    <td>{{user.registration_ip}}</td>
                                    <td>{{user.last_login_at * 1000 | date:'yyyy-MM-dd HH:mm:ss Z'}}</td>
                                    <td>
                                        <a ng-click="edit_user(user)" href="#" title="Edit" aria-label="Edit" onclick="return false;">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </a>
                                        <a ng-click="delete_user(user)" href="#" title="Delete" aria-label="Delete" onclick="return false;">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div data-ng-show="users.length == 0">
                            No results
                        </div>
                    </div>

                    <script type="text/ng-template" id="yesno.html">
                        <div class="modal-header">
                            <button type="button" class="close" ng-click="modalOptions.no('close')" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h3 class="modal-title">{{headerText}}</h3>
                        </div>
                        <div class="modal-body">{{bodyText}}</div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" ng-click="modalOptions.no('no')">{{No}}</button>
                            <button type="button" class="btn btn-primary"   ng-click="modalOptions.yes('yes')">{{Yes}}</button>
                        </div>
                    </script>

                    <script type="text/ng-template" id="edit.user">
                        <div class="modal-header">
                            <button type="button" class="close" ng-click="modalOptions.no('close')" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h3 class="modal-title">{{headerText}}</h3>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label" for="username">Username</label>
                                <input ng-model="username" type="text" id="username" class="form-control" autofocus="autofocus" tabindex="1" value="{{username}}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="email">Email</label>
                                <input ng-model="email" type="text" id="email" class="form-control" value="{{email}}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" ng-click="modalOptions.no('no')">{{No}}</button>
                            <button type="button" class="btn btn-primary"   ng-click="modalOptions.yes('save')">{{Yes}}</button>
                        </div>
                    </script>

                </div>

            </div>
        </div>
    </div>

    <script>

        var app = angular.module('app', [ 'controllers', 'ui.bootstrap' ]);
        var controllers = angular.module( 'controllers', []);

        controllers.controller('UsersController', ['$scope', 'UsersService', 'ModalService',
            function ($scope, UsersService, ModalService) {

                $scope.users = [];

                UsersService.get().then(function (data) {
                    if (data.status == 200)
                        $scope.users = data.data;
                }, function (err) {
                    $scope.alert = {type: 'danger', msg: err.statusText};
                    $scope.showAlert = true;
                    $scope.closeAlert = function() {
                        $scope.showAlert = false;
                    };
                });

                $scope.edit_user = function(user) {

                    var auth = <?= (Yii::$app->user->id)?1:0 ?>;
                    if (!auth){
                        $scope.showAlert = true;
                        $scope.closeAlert = function() {
                            $scope.showAlert = false;
                        };
                        $scope.alert = {type: 'danger', msg: 'Sign in'};
                        setTimeout('document.location = "/user/login"',2000);
                        return;
                    }

                    UsersService.user(user.id).then(function (data) {

                        var user = data.data;
                        var modalOptions = {
                            noButtonText: 'Cancel',
                            yesButtonText: 'Save',
                            headerText: 'Edit: ' + user.username,
                            username: user.username,
                            email: user.email,
                        };
                        var defaultOptions = {
                            templateUrl: 'edit.user',
                            size: 'md',
                        };

                        ModalService.showModal(defaultOptions, modalOptions).then(function (result) {
                            UsersService.put(user.id,result).then(function (data) {
                                $scope.users = [];
                                UsersService.get().then(function (data) {
                                    if (data.status == 200)
                                        $scope.users = data.data;
                                }, function (err) {
                                    $scope.alert = {type: 'danger', msg: err.statusText};
                                    $scope.showAlert = true;
                                    $scope.closeAlert = function() {
                                        $scope.showAlert = false;
                                    };
                                });
                            }, function (err) {
                                $scope.alert = {type: 'danger', msg: err.statusText};
                                $scope.showAlert = true;
                                $scope.closeAlert = function() {
                                    $scope.showAlert = false;
                                };
                            });
                        });

                    }, function (err) {
                        $scope.alert = {type: 'danger', msg: err.statusText};
                        $scope.showAlert = true;
                        $scope.closeAlert = function() {
                            $scope.showAlert = false;
                        };
                    });
                };

                $scope.delete_user = function(user) {

                    var modalOptions = {
                        bodyText: 'Delete '+user.username+' '+user.email+' ?',
                    };
                    var defaultOptions = {
                        size: 'sm',
                    };

                    ModalService.showModal(defaultOptions, modalOptions).then(function (result) {

                        if (result == 'yes'){
                            UsersService.delete(user.id).then(function (data) {
                                $scope.alert = {type: 'success', msg: 'Deleted'};
                                $scope.showAlert = true;
                                $scope.closeAlert = function() {
                                    $scope.showAlert = false;
                                };
                                $scope.users = [];
                                UsersService.get().then(function (data) {
                                    if (data.status == 200)
                                        $scope.users = data.data;
                                }, function (err) {
                                    $scope.alert = {type: 'danger', msg: err.statusText};
                                    $scope.showAlert = true;
                                    $scope.closeAlert = function() {
                                        $scope.showAlert = false;
                                    };
                                });
                            }, function (err) {
                                console.log(err);
                                $scope.showAlert = true;
                                $scope.closeAlert = function() {
                                    $scope.showAlert = false;
                                };
                                var user = <?= (Yii::$app->user->id)?1:0 ?>;
                                if (!user){
                                    $scope.alert = {type: 'danger', msg: 'Sign in'};
                                    setTimeout('document.location = "/user/login"',2000);
                                    return;
                                }
                                $scope.alert = {type: 'danger', msg: err.statusText};
                            });
                        }
                    });
                };
            }
        ]);

        app.service('UsersService', function($http) {
            this.get = function() {
                return $http.get('/api/users');
            };
            this.user = function (id) {
                return $http.get('/api/users/' + id);
            };
            this.post = function (data) {
                return $http.post('/api/users', data);
            };
            this.put = function (id, data) {
                return $http.put('/api/users/' + id, data);
            };
            this.delete = function (id) {
                return $http.delete('/api/users/' + id);
            };
        });

        app.service('ModalService', function($uibModal) {

            var modalDefaults = {
                backdrop: true,
                keyboard: true,
                modalFade: true,
                templateUrl: 'yesno.html'
            };
            var modalOptions = {
                headerText: 'Confirm',
                bodyText: 'Are you sure ?',
                yesButtonText: 'Yes',
                noButtonText: 'No'
            };

            this.showModal = function (customModalDefaults, customModalOptions) {
                if (!customModalDefaults){
                    customModalDefaults = {};
                }
                customModalDefaults.backdrop = 'static';
                return this.show(customModalDefaults, customModalOptions);
            };

            this.show = function (customModalDefaults, customModalOptions) {

                var tempModalDefaults = {};
                var tempModalOptions = {};
                angular.extend(tempModalDefaults, modalDefaults, customModalDefaults);
                angular.extend(tempModalOptions, modalOptions, customModalOptions);

                if (!tempModalDefaults.controller) {
                    tempModalDefaults.controller = ['$scope', '$uibModalInstance', function ($scope, $uibModalInstance) {
                        $scope.modalOptions = tempModalOptions;
                        $scope.modalOptions.no = $scope.modalOptions.yes = function (result) {
                            if (result == 'save'){
                                result = {
                                    username: $scope.username,
                                    email: $scope.email,
                                };
                            }
                            $uibModalInstance.close(result);
                        };
                        $scope.headerText = $scope.modalOptions.headerText;
                        $scope.bodyText = $scope.modalOptions.bodyText;
                        $scope.Yes = $scope.modalOptions.yesButtonText;
                        $scope.No = $scope.modalOptions.noButtonText;
                        $scope.username = $scope.modalOptions.username;
                        $scope.email = $scope.modalOptions.email;
                    }]
                    tempModalDefaults.controller.$inject = ['$scope', '$uibModalInstance'];
                }
                return $uibModal.open(tempModalDefaults).result;
            };
        });

    </script>

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <div class="row">

            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
