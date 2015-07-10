# Ansible Deployment Playbook

An ansible playbook for deploying a WordPress site from a git repository.

This is currently a proof of concept and likely isn't generic enough to work
for most deployment processes. Feel free to fork it and adapt to your needs.

We're using [Composer to manage the site stack](http://composer.rarst.net/recipe/site-stack) along with a [SatisPress](https://github.com/blazersix/satispress) instance to manage private packages. Only custom code is stored in site repository.

The repository and node modules are cached in a workspace to speed up subsequent deployments.


## Getting Started

Install [Ansible](http://docs.ansible.com/intro_installation.html) then run the following commands:

```sh
# Clone this repository.
git clone git@github.com:cedaro/ansible-deploy.git

# Change to the cloned repository
cd ansible-deploy

# Run the deploy playbook using the testing inventory
ansible-playbook -i testing deploy.yml
```

Running those commands will pull down an [example website](https://github.com/cedaro/example-website) and deploy it to your local machine at `/tmp/ansible-deploy`.


## Next Steps

* Create your own inventory using `group_vars/testing` and `roles/deploy/defaults/main.yml` for guidance.
* Read through the tasks in `roles/deploy/tasks` to see what tasks are run.


## Slack Integration

The `slack-command-handler.php` file serves as a quick demonstration for using a [Slack slash command](https://api.slack.com/slash-commands) to call an Ansible playbook. The handler should be placed in a web-accessible location.

After registering the command with Slack, a deployment can be kicked off from any channel with a command similar to the following:

```
/deploy production master
```


## Resources

* [npm without sudo](https://github.com/sindresorhus/guides/blob/master/npm-global-without-sudo.md)


## To Do

* Deploy a specific commit, branch, or tag (should already be possible with `--extra-vars`)
* Implement a rollback method
* Clean up old releases
* [Official deploy helper module](https://github.com/ansible/ansible-modules-extras/pull/110)
