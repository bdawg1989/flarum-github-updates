import app from 'flarum/admin/app';
import Modal from 'flarum/common/components/Modal';
import Button from 'flarum/common/components/Button';

export default class GitHubSettingsModal extends Modal {
  oninit(vnode) {
    super.oninit(vnode);
    this.repos = app.data.settings.githubRepos ? JSON.parse(app.data.settings.githubRepos) : [];
  }

  className() {
    return 'GitHubSettingsModal Modal--small';
  }

  title() {
    return 'GitHub Repositories';
  }

  content() {
    return (
      <div className="Modal-body">
        <div id="github-repos-list">
          {this.repos.map((repo, index) => (
            <div className="Form-group" key={index}>
              <input className="FormControl" type="text" value={repo} oninput={m.withAttr('value', this.updateRepo.bind(this, index))} />
              <Button className="Button Button--icon" icon="fas fa-times" onclick={this.removeRepo.bind(this, index)} />
            </div>
          ))}
        </div>
        <Button className="Button Button--primary" onclick={this.addRepo.bind(this)}>Add Repository</Button>
      </div>
    );
  }

  updateRepo(index, value) {
    this.repos[index] = value;
  }

  removeRepo(index) {
    this.repos.splice(index, 1);
  }

  addRepo() {
    this.repos.push('');
  }

  onsubmit(e) {
    e.preventDefault();
    app.data.settings.githubRepos = JSON.stringify(this.repos);
    app.request({
      method: 'POST',
      url: app.forum.attribute('apiUrl') + '/settings',
      body: { settings: { githubRepos: app.data.settings.githubRepos } },
    }).then(() => {
      app.alerts.show({ type: 'success' }, 'Settings saved');
      this.hide();
    });
  }
}
