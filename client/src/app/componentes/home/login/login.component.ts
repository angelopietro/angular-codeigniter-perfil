import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../../../core/services';
import { NotificationsService } from 'angular2-notifications';
import { first } from 'rxjs/operators';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  public timeOut = 3000;
  public hide = true;
  public isSubmitted = false;
  public loading = false;
  public formLogin: FormGroup;
  public digitosSenha = 6;
  public emailPattern = "^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$";
  public returnUrl: string;
  public error = '';
  public token: any;
  public submitPromise: Promise<any>;

  constructor(
    private formBuilder: FormBuilder,
    private Auth: AuthService,
    private alertaService: NotificationsService,
    private router: Router) { }

  ngOnInit() {
    this.formLogin = this.formBuilder.group({
      cEmail: ['', Validators.compose([Validators.required, Validators.pattern(this.emailPattern)])],
      cSenha: ['', [Validators.required, Validators.minLength(this.digitosSenha)]]
    });
  }

  get formControls() { return this.formLogin.controls; }

  loadButton() {
    return new Promise((fulfill) => {
      setTimeout(() => {
        fulfill({
          msg: 'SUCCESS'
        });
      }, this.timeOut);
    });
  }

  onLogin() {
    this.isSubmitted = true;

    if (this.formLogin.invalid) {
      this.loading = false;
      return;
    }

    this.submitPromise = this.loadButton();

    const userEmail = this.formControls.cEmail.value;
    const userPwd = this.formControls.cSenha.value;

    this.Auth.loginUser(userEmail, userPwd)
      .pipe(first())
      .subscribe(
        data => {
          if (data.status) {
            setTimeout(() => {
              let userJson = JSON.stringify(data);
              this.router.navigateByUrl('dashboard/perfil');
              localStorage.setItem('userLogged', userJson);
            }, this.timeOut);
          } else {
            setTimeout(() => {
              this.alertaService.error('Atenção!', data.message);
            }, this.timeOut);
            this.loading = false;
          }
        },
        error => {
          this.alertaService.error('Atenção', error);
          this.error = error;
          this.loading = false;
        });
  }
}
