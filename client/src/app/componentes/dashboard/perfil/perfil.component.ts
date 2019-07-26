import { Component, OnInit, OnDestroy, HostListener } from '@angular/core';
import { of, Subscription, Observable } from 'rxjs';
import { delay, filter } from 'rxjs/operators';
import { FormBuilder, FormGroup, FormControl, Validators, AbstractControl } from '@angular/forms';
import { MAT_MOMENT_DATE_FORMATS, MomentDateAdapter } from '@angular/material-moment-adapter';
import { DateAdapter, MAT_DATE_FORMATS, MAT_DATE_LOCALE } from '@angular/material/core';
import { PerfilService } from '../../../core/services/perfil.service';
import { ActivatedRoute, Router, NavigationEnd } from '@angular/router';
import { Profile } from '../../../shared/interfaces';
import { BasecidadesService } from '../../../core/services/basecidades.service';
import { NotificationsService } from 'angular2-notifications';

@Component({
  selector: 'app-perfil',
  templateUrl: './perfil.component.html',
  styleUrls: ['./perfil.component.css'],
  providers: [
    { provide: MAT_DATE_LOCALE, useValue: 'pt-BR' },
    { provide: DateAdapter, useClass: MomentDateAdapter, deps: [MAT_DATE_LOCALE] },
    { provide: MAT_DATE_FORMATS, useValue: MAT_MOMENT_DATE_FORMATS },
  ],
})
export class PerfilComponent implements OnInit, OnDestroy {

  public hide = true;
  public isLinear = true;
  public isLoading: boolean = false;
  public isLoadingEstados: boolean = false;
  public isLoadingCidades: boolean = false;
  public isSubmitted = false;
  public formUpdateProfile: FormGroup;
  public nivelSenha = ['#DD2C00', '#FF6D00', '#FFD600', '#AEEA00', '#00C853'];
  public digitosSenha = 6;
  public emailPattern = "^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$";
  //public cadastrar: Subscription;
  public perfil: Profile[];
  public dadosPerfil: any = {};
  public estados: any;
  public cidades: any;
  //subscription: Subscription;
  userID: any;

  constructor(
    private formBuilder: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private baseCidadesService: BasecidadesService,
    private profileService: PerfilService,
    private alertaService: NotificationsService) { }

  ngOnInit() {
    /*this.user.getSomeData().subscribe(data => {
      this.message = data.message
    });*/
    const userID = JSON.parse(localStorage.getItem('userLogged'));
    // console.log(JSON.parse(userID));
    this.getPerfil(userID.userID);

    /** Validação do Formulário */
    this.formValid();

    /** Retorna a lista de Estados do Brasil */
    this.getEstados();
  }

  formValid() {
    this.formUpdateProfile = this.formBuilder.group({
      id: [''],
      nome: ['', Validators.required],
      data_nasc: ['', Validators.required],
      cpf: ['', Validators.required],
      telefone: ['', Validators.required],
      genero: ['', Validators.required],
      endereco: ['', Validators.required],
      bairro: ['', Validators.required],
      complemento: [''],
      cidade: ['', Validators.required],
      estado: ['', Validators.required],
      cep: ['', Validators.required],
      email: ['', Validators.compose([
        Validators.required,
        Validators.pattern(this.emailPattern)
      ])],
      senha: ['', [Validators.required, Validators.minLength(this.digitosSenha)]]
    });
  }

  /** Retorna um FormArray com o nome 'formArray'. */
  get formControls() { return this.formUpdateProfile }

  /** Método para chamar os dados do Usuário no cadastro*/
  getPerfil(idPerfil) {
    this.isLoadingEstados = true;
    this.profileService.getProfile(idPerfil).subscribe(data => {
      this.dadosPerfil = data;
      this.getCidadeAtual(this.dadosPerfil.estado);
    },
      err => console.log(err),
      () => this.isLoadingEstados = false
    );
  }

  /** Método para chamar estados do banco de dados */
  getEstados() {
    this.isLoadingEstados = true;
    this.baseCidadesService.getEstados().subscribe(data => {
      this.estados = data;
    },
      err => console.log(err),
      () => this.isLoadingEstados = false
    );
  }

  getCidadeAtual(idEstado) {
    this.isLoadingCidades = true;
    this.baseCidadesService.getCidades(idEstado).subscribe(data => {
      this.cidades = data;
    },
      err => console.log(err),
      () => this.isLoadingCidades = false
    );
  }

  /** Método invocado após (selectionChange) do estado e popula as cidades*/
  onChangeUf(event) {
    this.isLoadingCidades = true;
    let value = event.value;
    this.baseCidadesService.getCidades(value).subscribe(data => {
      this.cidades = data;
    },
      err => console.log(err),
      () => this.isLoadingCidades = false
    );
  }


  /** Salva os dados do Cadastro */
  onSave() {
    this.isSubmitted = true;
    if (this.formUpdateProfile.invalid) {
      return;
    }
    this.isLoading = true;
    this.gotoTop();

    this.formUpdateProfile.disable();

    this.profileService.updateProfile(this.formUpdateProfile.value).subscribe(data => {
      return of({}).pipe(delay(3000)).subscribe(() => {
        this.isLoading = false;
        this.formUpdateProfile.enable();
        this.alertaService.success('Parabéns!', '<span class="notify">Dados atualizados com sucesso!</span>');
      });
    },
      error => {
        return of({}).pipe(delay(3000)).subscribe(() => {
          this.isLoading = false;
          this.alertaService.error('Atenção', 'Ocorreu um erro ao atualizar seus dados!');
        });
      });
  }

  gotoTop() {
    window.scroll({
      top: 0,
      left: 0,
      behavior: 'smooth'
    });
  }

  ngOnDestroy() {
    // this.subscription.unsubscribe();
  }

}
