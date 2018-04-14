function onSignIn(googleUser) {
    var profile = googleUser.getBasicProfile();

    $.ajax({
        url: loginGoogle,
        type: 'POST',
        data: {email: profile.getEmail()},
        success: function(data) {
            if (data) {
                location.href = basePath;
            } else {
                location.reload();
            }
            signOut();
        }
    });
}

function onRegisterIn(googleUser) {
    var profile = googleUser.getBasicProfile();
    $.ajax({
        url: registroGoogle,
        type: 'POST',
        data: {email: profile.getEmail()},
        success: function(data) {
            if (data) {
                onSignIn(googleUser);
            } else {
                location.reload();
            }
            signOut();
        }
    });
}

function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut();
  }
