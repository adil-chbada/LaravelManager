<!DOCTYPE html>
<html>
<head>
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify/dist/vuetify.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <style>
        #console {
            white-space: pre-line;
            background-color: #607D8B;
            padding: 20px;
            color: #c5c5c5;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
<div id="app">
    <v-app>
        <v-content>
            <div>
                <v-toolbar tabs>
                    <v-toolbar-side-icon></v-toolbar-side-icon>

                    <v-toolbar-title>LARAVEL MANAGER</v-toolbar-title>

                    <v-spacer></v-spacer>

                    <v-btn icon>
                        <v-icon>search</v-icon>
                    </v-btn>

                    <v-btn icon>
                        <v-icon>more_vert</v-icon>
                    </v-btn>

                </v-toolbar>
                <v-tabs
                        centered
                        color="cyan"
                        dark
                        icons-and-text
                >
                    <v-tabs-slider color="yellow"></v-tabs-slider>

                    <v-tab href="#tab-1">
                        configuration
                        <v-icon>build</v-icon>
                    </v-tab>
                    <v-tab href="#tab-2">
                        base de données
                        <v-icon>dns</v-icon>
                    </v-tab>
                    <v-tab href="#tab-3">
                        feedback
                        <v-icon>feedback</v-icon>
                    </v-tab>
                    <v-tab href="#tab-3">
                        Contacter le développeur
                        <v-icon>contact_mail</v-icon>
                    </v-tab>
                    <v-tab-item id="tab-1">
                        <v-card flat>
                            <v-card-text>
                                <v-card>
                                    <v-card-text>


                                        <v-card>
                                            <v-toolbar color="teal" dark>
                                                <v-toolbar-side-icon></v-toolbar-side-icon>

                                                <v-toolbar-title>Settings</v-toolbar-title>
                                            </v-toolbar>
                                            <v-layout row>
                                                <v-flex xs12 sm5>
                                                    <v-card flat>
                                                        <v-list
                                                                subheader
                                                                two-line
                                                                {{--style="max-height: 400px"--}}
                                                                {{--class="scroll-y"--}}
                                                        >
                                                            <template v-for="command in favCommands">
                                                                <v-subheader v-if="command.group">@{{ command.group }}</v-subheader>
                                                                <template v-for="cmd in command.commands">
                                                                    <v-list-tile @click="exec(cmd)" :style="{color:getCmdColor (cmd)}">
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>@{{ cmd.title }}</v-list-tile-title>
                                                                            <v-list-tile-sub-title>@{{ cmd.description }}</v-list-tile-sub-title>
                                                                            <v-progress-linear height="2" :indeterminate="true"
                                                                                               v-if="cmd===lastExec.cmd && lastExec.state==='inProgress'"
                                                                            ></v-progress-linear>
                                                                            <v-progress-linear
                                                                                    color="blue-grey"
                                                                                    height="2"
                                                                                    :indeterminate="true"
                                                                                    v-else-if="cmdWaiting.indexOf(cmd) > -1"
                                                                            ></v-progress-linear>

                                                                        </v-list-tile-content>

                                                                    </v-list-tile>
                                                                </template>
                                                            </template>


                                                        </v-list>
                                                    </v-card>
                                                </v-flex>
                                                <v-flex xs12 sm7>
                                                    <code id="console">
                                                        @{{lastExec.output}}
                                                    </code>
                                                </v-flex>

                                            </v-layout>
                                        </v-card>


                                    </v-card-text>

                                </v-card>

                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                </v-tabs>
            </div>
        </v-content>
    </v-app>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuetify/dist/vuetify.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>
    // import {AxiosInstance as axios} from "axios";

    new Vue({
        el: '#app',
        name: 'laravelManager',
        data() {
            return {
                tabs: null,
                commands: [
                    {
                        group: null,
                        commands: [
                            {title: 'down', description: 'Put the application into maintenance mode', cmd: 'down', fastExec: true, fav: true, state: 0},
                            {title: 'env', description: 'Display the current framework environment', cmd: 'env', fastExec: true, fav: true, state: 0},
                            {title: 'list', description: ' Lists commands', cmd: 'list', fastExec: true, fav: true, state: 0},
                            {title: 'migrate', description: 'Run the database migrations', cmd: 'migrate', fastExec: true, fav: false, state: 0},
                            {title: 'optimize', description: ' Cache the framework bootstrap files', cmd: 'optimize', fastExec: true, fav: false, state: 0},
                        ]


                    },
                    {
                        group: 'cache',
                        commands: [
                            {title: 'cache:clear', description: ' Flush the application cache', cmd: 'cache:clear', fastExec: true, fav: true, state: 0},
                            {title: 'cache:forget', description: ' Remove an item from the cache', cmd: 'cache:forget', fastExec: true, fav: false, state: 0},
                            {title: 'cache:table', description: 'Create a migration for the cache database table', cmd: 'cache:table', fastExec: true, fav: false, state: 0}
                        ]
                    },
                    {
                        group: 'config',
                        commands: [
                            {title: 'config:cache', description: ' Create a cache file for faster configuration loading', cmd: 'config:cache', fastExec: true, fav: true, state: 0},
                            {title: 'config:clear', description: ' Remove the configuration cache file', cmd: 'config:clear', fastExec: true, fav: true, state: 0},
                        ]
                    },
                    {group: 'db', commands: [{title: 'db:seed', description: 'Seed the database with records', cmd: 'db:seed', fastExec: true, fav: true, state: 0}]},
                    {group: 'key', commands: [{title: 'key:generate', description: 'Set the application key', cmd: 'key:generate', fastExec: true, fav: false, state: 0}]},
                    {
                        group: 'migrate',
                        commands: [
                            {title: 'migrate:fresh', description: 'Drop all tables and re-run all migrations', cmd: 'migrate:fresh', fastExec: true, fav: true, state: 0},
                            {title: 'migrate:refresh', description: ' Reset and re-run all migrations', cmd: 'migrate:refresh', fastExec: true, fav: false, state: 0},
                        ]
                    },
                    {group: 'optimize', commands: [{title: 'optimize:clear', description: ' Remove the cached bootstrap files', cmd: 'optimize:clear', fastExec: true, fav: false, state: 0}]},
                    {group: 'route', commands: [{title: 'route:list', description: 'List all registered routes', cmd: 'route:list', fastExec: true, fav: false, state: 0}]},
                    {
                        group: 'view',
                        commands: [
                            {title: 'view:cache', description: 'Compile all of the application\'s Blade templates', cmd: 'view:cache', fastExec: true, fav: false, state: 0},
                            {title: 'view:clear', description: 'Clear all compiled view files', cmd: 'view:clear', fastExec: true, fav: false, state: 0}
                        ]
                    },
                    // {group: '', commands: []},
                ],
                selectedCommand: null,
                lastExec: {
                    cmd: null,
                    state: null,
                    output: null,
                },
                cmdWaiting: [],
                // activeCmd:null,
            }
        },
        methods: {
            exec(cmd) {
                if (this.selectedCommand === cmd) {
                    this.cmdWaiting.push(cmd);
                    this.selectedCommand = null;
                    this.send();

                } else {
                    this.selectedCommand = cmd;
                }

            },
            send() {
                if (this.cmdWaiting.length > 0 && this.lastExec.state !== 'inProgress') {
                    this.lastExec.state = 'inProgress';
                    this.lastExec.cmd = this.cmdWaiting[0];
                    this.cmdWaiting.splice(0, 1);
                    axios.post('{{route('laravel_manager_exec')}}', {
                        cmd: this.lastExec.cmd
                    })
                        .then((response) => {
                            this.lastExec.state = 'ok';
                            this.lastExec.output = response.data;
                        })
                        .catch((error) => {
                            this.lastExec.state = 'err';
                            if (error.response)
                                this.lastExec.output = error.response.data.message;
                        }).finally(() => {
                        this.$vuetify.goTo('#console');
                        this.send();
                    });
                }
            },
            getCmdColor(cmd) {
                let color = null;
                if (this.selectedCommand === cmd) {
                    color = '#03A9F4';
                }
                else if (this.lastExec.cmd === cmd) {
                    if (this.lastExec.state === 'ok') {
                        return '#4CAF50';
                    }
                    else if (this.lastExec.state === 'err') {
                        return '#009688';
                    }
                } else {
                    return ''
                }
                return color;

            }
        },
        computed: {
            favCommands() {
                return this.commands.map(command => {
                    command.commands = command.commands.filter(cmd => cmd.fav);
                    return command
                }).filter(command => command.commands.length > 0);
            }
        }

    })
</script>
</body>
</html>
