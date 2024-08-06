import app from 'flarum/forum/app';
import { extend } from 'flarum/common/extend';
import SettingsPage from 'flarum/admin/components/SettingsPage';
import Button from 'flarum/common/components/Button';
import GitHubSettingsModal from './components/GitHubSettingsModal';

app.initializers.add('flarum-github-updates', () => {
  extend(SettingsPage.prototype, 'settingsItems', function (items) {
    items.add('githubRepositories', 
      <div className="Form-group">
        <label>GitHub Repositories</label>
        <Button className="Button Button--primary" onclick={() => app.modal.show(GitHubSettingsModal)}>Manage Repositories</Button>
      </div>
    );
  });

  function addGitHubRepo() {
    const repoList = document.getElementById('github-repos-list');
    const newRepoInput = document.createElement('input');
    newRepoInput.type = 'text';
    newRepoInput.className = 'FormControl';
    newRepoInput.placeholder = 'Enter GitHub repository (e.g., user/repo)';
    repoList.appendChild(newRepoInput);
  }
});
