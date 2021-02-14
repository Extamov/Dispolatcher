const configuration = {
	iceServers: [
		{url:'stun:stun.l.google.com:19302'},
		{url:'stun:stun2.l.google.com:19302'},
		{
			url: 'stun:numb.viagenie.ca',
			credential: 'A8DtJvWPZW5W7f',
			username: 'yeadha@protonmail.com'
		},
		{
			url: 'turn:numb.viagenie.ca',
			credential: 'A8DtJvWPZW5W7f',
			username: 'yeadha@protonmail.com'
		}
	]
};