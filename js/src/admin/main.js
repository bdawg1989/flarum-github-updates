import app from 'flarum/admin/app';
import GitHubSettingsModal from './components/GitHubSettingsModal';

app.initializers.add('flarum-github-updates', () => {
  app.extensionData
    .for('vendor-flarum-github-updates')
    .registerSetting({
      setting: 'vendor.flarum-github-updates.githubRepos',
      type: 'json',
      label: app.translator.trans('vendor-flarum-github-updates.admin.settings.github_repos_label'),
      help: app.translator.trans('vendor-flarum-github-updates.admin.settings.github_repos_help'),
      component: GitHubSettingsModal,
    });
});
