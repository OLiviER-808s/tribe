import { faIcons, faUser, faUserAstronaut } from '@fortawesome/free-solid-svg-icons'

export const COLORS = [
    'red',
    'orange',
    'yellow',
    'green',
    'teal',
    'blue',
    'indigo',
    'purple',
    'pink',
    'cyan',
    'sky',
    'lime',
    'amber',
    'violet',
    'rose',
    'fuchsia',
    'emerald'
]

export const DEFAULT_PROFILE_PIC = '/default_profile.jpg'

export const REGISTER_STEPS = [
    { label: 'Account', value: 'account', icon: faUser },
    { label: 'Profile', value: 'profile', icon: faUserAstronaut },
    { label: 'Interests', value: 'interests', icon: faIcons }
]