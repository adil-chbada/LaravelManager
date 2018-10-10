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
                                                            <v-checkbox v-model="force">--force</v-checkbox>
                                                            <template v-for="command in favCommands">
                                                                <v-subheader v-if="command.group">@{{ command.group }}
                                                                </v-subheader>
                                                                <template v-for="cmd in command.commands">
                                                                    <v-list-tile @click="exec(cmd)"
                                                                                 :style="{color:getCmdColor (cmd)}">
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>@{{ cmd.title }}
                                                                               @{{cmd.options}}
                                                                                <template v-for="option in cmd.options ">
                                                                                    <v-checkbox v-model="option.value" :title="option.title"></v-checkbox>
                                                                                </template>
                                                                            </v-list-tile-title>
                                                                            <v-list-tile-sub-title>@{{ cmd.description
                                                                                }}
                                                                            </v-list-tile-sub-title>
                                                                            <v-progress-linear height="2"
                                                                                               :indeterminate="true"
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

    function cmd_(title = null, cmd, description, options = [], fav, state = 0) {
        cmd = (cmd !== null) ? cmd : title;
        return {
            title: title,
            description: description,
            cmd: cmd,
            options: options,
            fav: fav,
            state: state,
            o(script, value = true, title = null) {
                title = (title !== null) ? title : script
                $option = this.options.find(function ($option) {
                    return $option.script == script;
                });
                if ($option) {
                    $option.value = value
                } else {
                    this.options.push({script: script, title: title, value: value})
                }
                return this;
            },
            o_(script, value = false, title = null) {
                return this.o(script, value, title)
            }
        }
    }

    function cmd(cmd, description, options = []) {
        return cmd_(cmd, cmd, description, options, true, 0)
    }

    new Vue({
        el: '#app',
        name: 'laravelManager',
        data() {
            return {
                tabs: null,
                force: false,
                commands: [
                    {
                        group: null,
                        commands: [
                            cmd('down', 'Put the application into maintenance mode', []).o_('--force').o_('non'),
                            cmd('env', 'Display the current framework environment', []),
                            cmd('list', 'Lists commands', []),
                            // cmd( 'migrate', 'Run the database migrations', []),
                            // cmd( 'optimize', 'Cache the framework bootstrap files', []),
                        ]


                    },
                    {
                        group: 'cache',
                        commands: [
                            cmd('cache:clear', 'Flush the application cache', []),
                            // cmd( 'cache:forget', 'Remove an item from the cache', []),
                            // cmd( 'cache:table', 'Create a migration for the cache database table', []),
                        ]
                    },
                    {
                        group: 'config',
                        commands: [
                            cmd('config:cache', 'Create a cache file for faster configuration loading', []),
                            cmd('config:clear', 'Remove the configuration cache file', []),
                        ]
                    },
                    {
                        group: 'db',
                        commands: [
                            cmd('db:seed', 'Seed the database with records', []),
                        ]
                    },
                    {
                        group: 'key',
                        commands: [
                            cmd('key:generate', 'Set the application key', []),
                        ]
                    },
                    {
                        group: 'migrate',
                        commands: [
                            cmd('migrate:fresh', 'Drop all tables and re-run all migrations', []),
                            // cmd( 'migrate:refresh', 'Reset and re-run all migrations', []),
                        ]
                    },
                    {
                        group: 'optimize',
                        commands: [
                            // cmd( 'optimize:clear', 'Remove the cached bootstrap files', [])
                        ]
                    },
                    {
                        group: 'route',
                        commands: [
                            cmd('route:list', 'List all registered routes', []),
                        ]
                    },
                    {
                        group: 'view',
                        commands: [
                            // cmd( 'view:cache', 'Compile all of the application\\\'s Blade templates', []),
                            cmd('view:clear', 'Clear all compiled view files', []),
                            // cmd( '', '', []),
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
                    if (this.force)
                        this.lastExec.cmd.o('--force')
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
