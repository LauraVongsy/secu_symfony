// describe('test utilisateur', () => {
//   it('ajout de compte', () => {
//     cy.visit('http://localhost:8000/register')
//     cy.get('#register_firstname').type('laura')
//     cy.get('#register_name').type('vng')
//     cy.get('#register_email').type('laura.test@gmail.com')
//     cy.get('#register_password_first').type('coucou')
//     cy.get('#register_password_second').type('coucou')
//     cy.get('#register_valider').click()
//     // cy.get('strong').should('contain', 'Le compte : laura.test@gmail.com existe déja')
//     cy.get('strong').invoke("text").then((text => {
//       if (text == "Le compte : mail@mail.fr existe déja") {
//         cy.log("Doublon")
//       } else {
//         cy.log("le compte a été ajouté en bdd")
//       }
//     }))
//   })
// })

describe('Test utilisateur', () => {
  it('Modification du compte existe', () => {
    cy.visit('https://localhost:8000/register/update/1')
    cy.get('#register_firstname').clear().type("Mathieu2")
    cy.get('#register_name').clear().type("Mithridate2")
    cy.get('#register_email').clear().type("mathieu.mith@laposte.net")
    cy.get('#register_password_first').clear().type("1234")
    cy.get('#register_password_second').clear().type("1234")
    cy.get('#register_Ajouter').click()
    cy.get('strong').should("contain", "Le compte a été mis à jour en BDD")
  })
  it('Modification du compte n\'existe pas', () => {
    cy.visit('https://localhost:8000/register/update/2')
    cy.get('strong').should("contain", "le compte n'existe pas")
  })
})