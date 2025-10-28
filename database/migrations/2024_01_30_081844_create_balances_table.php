<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balances', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index();
            $table->date('date')->index();

            $table->integer('in_count')->default(0);
            $table->decimal('in_amount', 14)->default(0);
            $table->integer('out_count')->default(0);
            $table->decimal('out_amount', 14)->default(0);
            $table->decimal('balance', 14)->default(0)->index();

            $table->decimal('in_withdrawable_amount', 14)->default(0);
            $table->decimal('out_withdrawable_amount', 14)->default(0);
            $table->decimal('withdrawable_balance', 14)->default(0)->index();

            $table->decimal('in_bonus_amount', 14)->default(0);
            $table->decimal('out_bonus_amount', 14)->default(0);
            $table->decimal('bonus_balance', 14)->default(0)->index();

            $table->timestamps();
            $table->softDeletes();
            $table->unique(['date', 'user_id'], 'OneRowPerUser');
        });

        DB::statement("
        CREATE OR REPLACE FUNCTION \"public\".\"update_user_balance\"()
  RETURNS \"pg_catalog\".\"trigger\" AS \$BODY\$
        BEGIN
        -- Try block starts
            BEGIN
            -- İlgili müşteriye ve tarihe ait satırı kilitleyin
            PERFORM * FROM balances WHERE user_id = NEW.user_id AND date = date_trunc('day', NEW.created_at) FOR UPDATE;

            -- Eğer işlem siliniyorsa
            IF TG_OP = 'DELETE' AND OLD.status = 4 THEN  -- completed = 4

                IF NOT EXISTS (SELECT 1 FROM balances WHERE user_id = OLD.user_id AND date = date_trunc('day', OLD.created_at)) THEN
                    INSERT INTO balances (user_id, date) VALUES (OLD.user_id, date_trunc('day', OLD.created_at));
                END IF;

                -- OLD icin IN silme
                IF OLD.purpose = 1 THEN  -- in=1
                    UPDATE balances
                    SET in_count = in_count - 1,
                        in_amount = in_amount - OLD.amount,
												balance = balance - OLD.amount,
												in_withdrawable_amount = in_withdrawable_amount - OLD.withdrawable_amount,
												withdrawable_balance = withdrawable_balance - OLD.withdrawable_amount,
												in_bonus_amount = in_bonus_amount - OLD.bonus_amount,
												bonus_balance = bonus_balance - OLD.bonus_amount
                    WHERE user_id = OLD.user_id AND date = date_trunc('day', OLD.created_at);
                END IF;

                -- OLD icin OUT silme
                IF OLD.purpose = 2 THEN  -- out=2
                    UPDATE balances
                    SET out_count = out_count - 1,
                        out_amount = out_amount - OLD.amount,
												balance = balance + OLD.amount,
												out_withdrawable_amount = out_withdrawable_amount - OLD.withdrawable_amount,
												withdrawable_balance = withdrawable_balance + OLD.withdrawable_amount,
												out_bonus_amount = out_bonus_amount - OLD.bonus_amount,
												bonus_balance = bonus_balance + OLD.bonus_amount
                    WHERE user_id = OLD.user_id AND date = date_trunc('day', OLD.created_at);
                END IF;

            -- Eğer işlem güncelleniyorsa
            ELSE
            -- Old transaction update
                IF OLD.status = 4 THEN  -- completed = 4
                     PERFORM * FROM balances WHERE user_id = OLD.user_id AND date = date_trunc('day', OLD.created_at) FOR UPDATE;

                    -- OLD için IN güncelleme
                    IF OLD.purpose = 1 THEN  -- in=1
                            UPDATE balances
                            SET in_count = in_count - 1,
                                    in_amount = in_amount - OLD.amount,
																		balance = balance - OLD.amount,
																		in_withdrawable_amount = in_withdrawable_amount - OLD.withdrawable_amount,
																		withdrawable_balance = withdrawable_balance - OLD.withdrawable_amount,
																		in_bonus_amount = in_bonus_amount - OLD.bonus_amount,
																		bonus_balance = bonus_balance - OLD.bonus_amount
                            WHERE user_id = OLD.user_id AND date = date_trunc('day', OLD.created_at);
                    END IF;

                    -- OLD için OUT guncelleme
                    IF OLD.purpose = 2 THEN  -- out=2
                            UPDATE balances
                            SET out_count = out_count - 1,
                                    out_amount = out_amount - OLD.amount,
																		balance = balance + OLD.amount,
																		out_withdrawable_amount = out_withdrawable_amount - OLD.withdrawable_amount,
                                    withdrawable_balance = withdrawable_balance + OLD.withdrawable_amount,
																		out_bonus_amount = out_bonus_amount - OLD.bonus_amount,
                                    bonus_balance = bonus_balance + OLD.bonus_amount
                            WHERE user_id = OLD.user_id AND date = date_trunc('day', OLD.created_at);
                    END IF;

                END IF;

                -- New transaction update
                IF NEW.status = 4 THEN  -- completed = 4
                    IF NOT EXISTS (SELECT 1 FROM balances WHERE user_id = NEW.user_id AND date = date_trunc('day', NEW.created_at)) THEN
                        INSERT INTO balances (user_id, date) VALUES (NEW.user_id, date_trunc('day', NEW.created_at));
                    END IF;

                    -- IN
                    IF NEW.purpose = 1 THEN  -- in=1
                            UPDATE balances
                            SET in_count = in_count + 1,
                                    in_amount = in_amount + NEW.amount,
																		balance = balance + NEW.amount,
																		in_withdrawable_amount = in_withdrawable_amount + NEW.withdrawable_amount,
                                    withdrawable_balance = withdrawable_balance + NEW.withdrawable_amount,
																		in_bonus_amount = in_bonus_amount + NEW.bonus_amount,
                                    bonus_balance = bonus_balance + NEW.bonus_amount
                            WHERE user_id = NEW.user_id AND date = date_trunc('day', NEW.created_at);
                    END IF;

                    -- OUT
                    IF NEW.purpose = 2 THEN  -- out=2
                            UPDATE balances
                            SET out_count = out_count + 1,
                                    out_amount = out_amount + NEW.amount,
																		balance = balance - NEW.amount,
																		out_withdrawable_amount = out_withdrawable_amount + NEW.withdrawable_amount,
																		withdrawable_balance = withdrawable_balance - NEW.withdrawable_amount,
																		out_bonus_amount = out_bonus_amount + NEW.bonus_amount,
																		bonus_balance = bonus_balance - NEW.bonus_amount
                            WHERE user_id = NEW.user_id AND date = date_trunc('day', NEW.created_at);
                    END IF;

                END IF;

            END IF;

             -- Catch block starts for exceptions
            EXCEPTION
                WHEN OTHERS THEN
                            PERFORM pg_sleep(0.01); -- 10 milisaniye beklet
                    RAISE NOTICE 'An error occurred: %', SQLERRM;
                    -- Raise the exception again so that the original INSERT/DELETE operation on transactions is also rolled back
                    RAISE;

            END;  -- End of BEGIN ... EXCEPTION block

            RETURN NEW;
        END;
        \$BODY\$
  LANGUAGE plpgsql VOLATILE
  COST 100
        ");


        DB::statement('CREATE TRIGGER "trigger_balances" AFTER INSERT OR UPDATE OF "user_id", "amount", "withdrawable_amount", "bonus_amount", "purpose", "type", "status", "created_at" OR DELETE ON "public"."transactions"
                            FOR EACH ROW
                            EXECUTE PROCEDURE "public"."update_user_balance"();');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('balances');
        DB::statement("DROP FUNCTION IF EXISTS update_user_balance;");
        DB::statement("DROP TRIGGER IF EXISTS trigger_balances;");
    }
};
