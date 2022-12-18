import { Connect, Settings, Signup, Activate } from '@components/onboarding';

export const routes = [
	{ path: '', component: Connect },
	{ path: 'settings', component: Settings },
	{ path: 'signup', component: Signup },
	{ path: 'activate', component: Activate }
];