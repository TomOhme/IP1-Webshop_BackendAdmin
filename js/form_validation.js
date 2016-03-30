function update_user_validation() {
    $('#create').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            username: {
                message: 'Benutzername ist ungültig',
                validators: {
                    notEmpty: {
                        message: 'Bitte Benutzername angeben'
                    },
                    stringLength: {
                        min: 2,
                        max: 30,
                        message: 'Der Benutzername muss zwischen 2 und 30 Zeichen lang sein'
                    },
                    remote: {
                        message: 'Ein Nutzer mit diesem Benutzernamen existiert bereits',
                        data: function(validator) {
                            return {
                            	username: validator.getFieldElements('username').val(),
                            	id: validator.getFieldElements('id').val()
                            };
                        },
                        url: 'pages.php?site=validate_username'
                    }
                    
                }
            },
            firstname: {
                message: 'Bitte Vornamen angeben',
                validators: {
                    notEmpty: {
                        message: 'Bitte Vornamen angeben'
                    }
                }
            },
            lastname: {
                message: 'Bitte Nachname angeben',
                validators: {
                    notEmpty: {
                        message: 'Bitte Nachname angeben'
                    }
                }
            },
            email: {
                message: 'Email ungültig',
                validators: {
                    notEmpty: {
                        message: 'Bitte Email angeben'
                    },
                    emailAddress: {
                        message: 'Ungültige Email'
                    },
                    remote: {
                        message: 'Ein Nutzer mit dieser Email existiert bereits',
                        data: function(validator) {
                            return {
                            	email: validator.getFieldElements('email').val(),
                            	id: validator.getFieldElements('id').val()
                            };
                        },
                        url: 'pages.php?site=validate_email'
                    }
                }
            },
            password: {
                message: 'Ungültiges Passwort',
                validators: {
                    notEmpty: {
                        message: 'Kein Passwort angegeben'
                    },
                    stringLength: {
                        min: 4,
                        max: 30,
                        message: 'Das Passwort muss zwischen 4 und 30 Zeichen lang sein'
                    }
                }
            }
        }
    }).on('success.form.bv', function(e) {
        // Prevent form submission
        e.preventDefault();

        // Use Ajax to submit form data
        updateUser();
        
    });
    return false;
};

function update_article_validation() {	
    $('#create').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            title: {
                message: 'Artikelname ist ungültig',
                validators: {
                    notEmpty: {
                        message: 'Bitte Artikelname angeben'
                    },
                    remote: {
                        message: 'Ein Artikel mit diesem Titel existiert bereits',
                        data: function(validator) {
                            return {
                            	title: validator.getFieldElements('title').val(),
                                sku: validator.getFieldElements('sku').val()
                            };
                        },
                        url: 'pages.php?site=validate_article'
                    },
                    stringLength: {
                        min: 2,
                        max: 50,
                        message: 'Artikelname muss zwischen 2 und 50 Zeichen sein'
                    }
                }
            },
            description: {
                message: 'Beschreibung ist ungültig',
                validators: {
                    stringLength: {
                        min: 0,
                        max: 250,
                        message: 'Beschreibung darf nicht länger als 250 Zeichen sein'
                    }
                }
            },
            stock: {
                message: 'Anzahl ist ungültig',
                validators: {
                    notEmpty: {
                        message: 'Bitte Anzahl angeben'
                    },
                    digits: {
                        message: 'Anzahl kann nur Zahlen enthalten'
                    }
                }
            },
            price: {
                message: 'Preis ist ungültig',
                validators: {
                    notEmpty: {
                        message: 'Bitte Preis angeben'
                    },
                    regexp: {
                        regexp: /^\d+([.,]\d{1,2})?$/,
                        message: 'Preis kann nur Zahlen enthalten'
                    }
                }
            }
        }
    }).on('success.form.bv', function(e) {
        // Prevent form submission
        e.preventDefault();

        // Use Ajax to submit form data
        updateArticle();
        
    });;
};

function import_articles_validation(){
    $('#groupForm').bootstrapValidator({
    	framework: 'bootstrap',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
        	'title[]': {
                message: 'Artikelname ist ungültig',
                validators: {
                    notEmpty: {
                        message: 'Bitte Artikelname angeben'
                    },
                    remote: {
                        message: 'Ein Artikel mit diesem Titel existiert bereits',
                        data: function(validator) {
                            return {
                            	title: validator.getFieldElements('title').val(),
                                sku: validator.getFieldElements('sku').val()
                            };
                        },
                        url: 'pages.php?site=validate_article'
                    },
                    stringLength: {
                        min: 2,
                        max: 50,
                        message: 'Artikelname muss zwischen 2 und 50 Zeichen sein'
                    }
                }
            },
            
            'category[]': {
                message: 'Beschreibung ist ungültig',
                validators: {
                    notEmpty: {
                        message: 'Bitte eine Kategorie angeben'
                    },
                	remote: {
                        message: 'Kategorie nicht gefunden',
                        data: function(validator) {
                            return {
                            	name: validator.getFieldElements('category[]').val(),
                                id: -1
                            };
                        },
                        url: 'pages.php?site=validate_category'
                    }
                }
            },
            'description[]': {
                message: 'Beschreibung ist ungültig',
                validators: {
                    stringLength: {
                        min: 0,
                        max: 250,
                        message: 'Beschreibung darf nicht länger als 250 Zeichen sein'
                    }
                }
            },
            'stock[]': {
                message: 'Anzahl ist ungültig',
                validators: {
                    notEmpty: {
                        message: 'Bitte Anzahl angeben'
                    },
                    digits: {
                        message: 'Anzahl kann nur Zahlen enthalten'
                    }
                }
            },
            'price[]': {
                message: 'Preis ist ungültig',
                validators: {
                    notEmpty: {
                        message: 'Bitte Preis angeben'
                    },
                    regexp: {
                        regexp: /^\d+([.,]\d{1,2})?$/,
                        message: 'Preis kann nur Zahlen enthalten'
                    }
                }
            },
            'category[]': {
                message: 'Bitte eine Kategorie wählen',
                validators: {
                    notEmpty: {
                        message: 'Bitte eine Kategorie wählen'
                }
            }
            }
        }
    }).on('success.form.bv', function(e) {
    	e.preventDefault();
    	
    	importArticles();
    	
    });
	
};

function update_category_validation() {
    $('#create').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            name: {
                message: 'Kategoriename ist ungültig',
                validators: {
                    notEmpty: {
                        message: 'Bitte einen Kategorienamen angeben'
                    },
                    stringLength: {
                        min: 2,
                        max: 50,
                        message: 'Kategoriename muss zwischen 2 und 50 Zeichen sein'
                    }
                }
            }
        }
    }).on('success.form.bv', function(e) {
    	
        // Prevent form submission
        e.preventDefault();
        // Use Ajax to submit form data
        updateCategory();
        
    });
};
